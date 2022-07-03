<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt\Hash;
use App\Helpers\Routers\Request;
use App\Helpers\Routers\Response;
use App\Mail\ForgotPassword;
use App\Models\Access;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Fidelity;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Reset;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use DateTime;
use Ramsey\Uuid\Uuid;

class UserController {
    public function me(Request $request) {
        return view("account.me");
    }
    public function editMe(Request $request) {
        $user = $request->user;
        $errors = $request->validate([
            "firstname" => "required",
            "lastname" => "required",
            "username" => "required|username",
            "email" => "required|email",
        ]);
        if (!$errors && User::where("email", $request->body["email"])->where("id", "!=", $user->id)->first()) {
            $errors = ["Email already taken."];
        }
        if ($errors) return Response::Json(400, $errors);
        
        $user->firstname = $request->body["firstname"];
        $user->lastname = $request->body["lastname"];
        $user->username = $request->body["username"];
        $user->email = $request->body["email"];

        if ($birthday = $request->body["birthday"]) {
            try {
                $user->birthAt = new DateTime($birthday);
            } catch (\Throwable $th) {}
        } else $user->birthAt = null;

        $user->save();
        return Response::Json(200, "Account information changed successfully.");
    }
    
    public function security(Request $request) {
        return view("account.security");
    }
    public function editSecurity(Request $request) {
        $user = $request->user;
        $errors = $request->validate([
            "old_password" => "required",
            "password" => "required|confirm",
        ]);
        if (!$errors && !Hash::check($request->body["old_password"], $user->password)) {
            $errors = ["Old password invalid."];
        }
        if ($errors) return Response::Json(400, $errors);

        $user->password = Hash::make($request->body["password"]);
        $user->save();

        return Response::Json(200, "Password changed successfully.");
    }
    
    public function addresses(Request $request) {
        return view("account.addresses", [
            "addresses" => array_map(fn (Address $a) => $a->toArray(), $request->user->addresses)
        ]);
    }
    public function deleteAddress(Request $request) {
        $address = Address::where("id", $request->params["id"])->first();
        if (!$address) return Response::Json(400, ["Address invalid."]);
        
        $address->destroy();

        return Response::Json(200, "Address deleted successfully.");
    }
    
    public function orders(Request $request) {
        $user = $request->user;
        $orders = [];
        $ods = $user->orders;
        usort($ods, fn (Order $a, Order $b) => $a->createdAt->getTimestamp() < $b->createdAt->getTimestamp() ? 1 : -1);

        foreach ($ods as $order) {
            $payment = $order->payment;
            if ($payment->status !== "FINISH") continue;

            $products = array_map(function (OrderProduct $orderProduct) use ($user) {
                $size = $orderProduct->size;
                $product = $size->product;
                $image = $product->images[0]->source;
                $review = Review::where([
                    "userId" => $user->id,
                    "productId" => $product->id
                ])->first();

                return [
                    "id" => $product->id,
                    "name" => $product->name,
                    "slug" => slugify("{$product->name} {$product->color}"),
                    "color" => $product->color,
                    "size" => $size->size,
                    "price" => $orderProduct->price,
                    "quantity" => $orderProduct->quantity,
                    "image" => $image,
                    "review" => $review ? [
                        "note" => $review->note,
                        "content" => $review->content
                    ] : null
                ];
            }, $order->orderProducts);

            $price = array_reduce($products, fn (float $r, array $p) => $r + $p["price"] * $p["quantity"], 0);

            array_push($orders, [
                "number" => $order->number,
                "price" => $price * 1.2,
                "date" => formatDate($order->payment->updatedAt, false),
                "products" => $products
            ]);
        }

        return view("account.orders", [
            "orders" => $orders
        ]);
    }

    public function rateProduct(Request $request) {
        $errors = $request->validate([
            "note" => "required|min:1|max:1|digit",
        ]);
        $note = intval($request->body["note"]);

        if (!$errors && ($note < 1 || $note > 5)) $errors = ["The note must be between 1 and 5."];
        if (!$errors && !($product = Product::where("id", $request->params["id"])->first())) $errors = ["Invalid product."];
        if ($errors) return Response::Json(400, $errors);

        $review = Review::where("userId", $request->user->id)->where("productId", $product->id)->first();
        if ($review) {
            $review->note = $note;
            $review->save();
        } else {
            $review = Review::create([
                "note" => $note,
                "userId" => $request->user->id,
                "productId" => $product->id
            ]);    
        }
        return Response::Json(200, "Successfully rated.");
    }

    public function commentProduct(Request $request) {
        $errors = $request->validate([
            "content" => "required|min:4|max:400",
        ]);

        if (!$errors && !($product = Product::where("id", $request->params["id"])->first())) $errors = ["Invalid product."];
        if (!$errors && !($review = Review::where(["productId" => $product->id, "userId" => $request->user->id])->first())) $errors = ["Invalid review."];
        if ($errors) return Response::Json(400, $errors);

        $review->content = $request->body["content"];
        $review->save();
        
        return Response::Json(200, "Successfully commented.");
    }
    
    
    public function reviews(Request $request) {
        $user = $request->user;
        $reviews = [];

        foreach ($user->reviews as $review) {
            $product = $review->product;
            $image = $product->images[0]->source;

            array_push($reviews, [
                "id" => $review->id,
                "note" => $review->note,
                "content" => $review->content,
                "product" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "slug" => slugify("{$product->name} {$product->color}"),
                    "color" => $product->color,
                    "image" => $image
                ]
            ]);
        }
        return view("account.reviews", [
            "reviews" => $reviews
        ]);
    }
    public function deleteReview(Request $request) {
        $review = Review::where("id", $request->params["id"])->first();
        if (!$review) return Response::Json(400, ["Invalid review."]);

        $review->destroy();
        return Response::Json(200, "Successfully deleted.");
    }
}