<?php

namespace App\Http\Controllers;

use App\Helpers\Payments\Invoice;
use App\Helpers\Payments\Order;
use App\Helpers\Payments\Stripe;
use App\Helpers\Routers\Request;
use App\Helpers\Routers\Response;
use App\Mail\PaymentConfirmation;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Order as ModelsOrder;
use App\Models\OrderProduct;
use App\Models\Payment;
use App\Models\ProductSize;

class CartController {
    public function addProduct(Request $request) {
        $size = ProductSize::where("id", $request->params["id"])->first();
        if (!$size) return Response::Json(400, ["Invalid productId."]);

        $cart = $request->cart;
        $cart->addSize($size);

        return Response::Json(200, [
            "count" => $cart->count,
            "message" => "Product added to your cart."
        ]);
    }

    public function removeProduct(Request $request) {
        $size = ProductSize::where("id", $request->params["id"])->first();
        if (!$size) return Response::Json(400, ["Invalid productId."]);

        $cart = $request->cart;
        $cart->removeSize($size);

        return Response::Json(200, [
            "count" => $cart->count
        ]);
    }

    public function deleteProduct(Request $request) {
        $size = ProductSize::where("id", $request->params["id"])->first();
        if (!$size) return Response::Json(400, ["Invalid productId."]);

        $cart = $request->cart;
        $cart->removeSize($size, true);

        return Response::Json(200, [
            "count" => $cart->count
        ]);
    }

    private function getProducts(Cart $cart) {
        $sizes = $cart->sizes;
        $products = [];
        foreach ($sizes as $size) {
            $index = -1;
            foreach ($products as $i => $p) if ($p["product"]["id"] === $size->id) {
                $index = $i;
                break;
            }
            if ($index >= 0) $products[$index]["count"]++;
            else {
                $product = $size->product;
                $image = $product->images[0];
                array_push($products, [
                    "product" => [
                        "id" => $size->id,
                        "productId" => $product->id,
                        "name" => $product->name,
                        "description" => $product->description,
                        "image" => $image->source,
                        "price" => $product->price,
                        "promo" => $product->promo,
                        "color" => $product->color,
                        "fidelity" => $product->fidelity,
                        "size" => $size->size,
                        "slug" => slugify($product->name . " {$product->color}")
                    ],
                    "count" => 1
                ]);
            }
        }

        return $products;
    }

    private function getPrice(array $products) {
        $price = array_reduce($products, fn ($r, array $p) => $r + $p["count"] * ($p["product"]["promo"] ?? $p["product"]["price"]), 0);
        return $price;
    } 

    public function showCheckout(Request $request) {
        $products = $this->getProducts($request->cart);
        $price = $this->getPrice($products);
        
        return view("cart.checkout", [
            "products" => $products,
            "price" => $price,
            "addresses" => $request->user ? array_map(fn (Address $a) => $a->toArray(), $request->user->addresses) : []
        ]);
    }

    public function createCheckout(Request $request) {
        $errors = $request->validate([
            "email" => "required|email",
            "addressId" => "required|digit"
        ]);
        if (!$errors && $request->body["addressId"] === "0") {
            $errors = $request->validate([
                "street" => "required|min:8",
                "zip" => "required|min:2",
                "city" => "required|min:2",
                "country" => "required|min:2",
                "name" => "required|min:2"
            ]);
        } else if (!$errors) {
            $address = Address::where("id", $request->body["addressId"])->first();
            if (!$address) $errors = ["Unknown address."];
        }
        if ($errors) return Response::Json(400, $errors);

        if ($request->body["addressId"] === "0") {
            $address = Address::create([
                "name" => $request->body["name"],
                "street" => $request->body["street"],
                "postal" => $request->body["zip"],
                "city" => $request->body["city"],
                "country" => $request->body["country"],
                "userId" => $request->user ? $request->user->id : null
            ]);
        }

        $email = $request->body["email"];

        $products = $this->getProducts($request->cart);
        $order = Order::create($products, $address, $email);
        $session = Stripe::createSession($products, $order, $email);

        if (!$session) {
            $order->destroy();
            return Response::Json(400, ["Error during session creation, please retry later."]);
        }

        Payment::create([
            "status" => "PENDING",
            "token" => $session->id,
            "orderId" => $order->id
        ]);

        return Response::Json(200, $session->url);
    }

    public function confirmPayment(Request $request) {
        $id = $request->params["id"];
        $order = ModelsOrder::where("number", $id)->first();
        if (!$order) return redirectWithAlerts("/", [[
            "type" => "error",
            "message" => "Order invalid."
        ]]);

        $payment = $order->payment;
        $session = Stripe::getSession($payment);

        if (!$session || $session->payment_status !== "paid") return redirectWithAlerts("/", [[
            "type" => "error",
            "message" => "Session invalid."
        ]]);
        if ($payment->status === "FINISH") return view("payment.confirm");

        $payment->status = "FINISH";
        $payment->save();

        $invoice = Invoice::generate($order);
        $name = config("app")["name"];
        PaymentConfirmation::withAttachments([[
            "ContentType" => "image/pdf",
            "Filename" => "invoice-{$order->number}.pdf",
            "File" => $invoice
        ]])->subject("{$name}: Payment receipt")->with([
            "name" => $order->address->name,
            "number" => $order->number
        ])->send($order->email);

        if ($user = $request->user) {
            $price = array_reduce($order->orderProducts, fn (float $r, OrderProduct $op) => $r + ($op->price * $op->quantity), 0);
            $fidelity = $user->fidelity;
            $fidelity->points += floor($price);
            $fidelity->save();
        }
        
        

        $request->cart->removeAllSize();
        view("payment.confirm");
    }

    public function cancelPayment(Request $request) {
        $id = $request->params["id"];
        $order = ModelsOrder::where("number", $id)->first();
        if (!$order) return redirect("/");

        $payment = $order->payment;
        $payment->status = "CANCEL";
        $payment->save();

        return redirectWithAlerts("/", [[
            "type" => "error",
            "message" => "Your payment has been canceled."
        ]]);
    }

}