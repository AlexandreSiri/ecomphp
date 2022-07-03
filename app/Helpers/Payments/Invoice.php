<?php

namespace App\Helpers\Payments;

use App\Models\Order;
use App\Models\OrderProduct;
use Konekt\PdfInvoice\InvoicePrinter;

class Invoice {
    static function generate(Order $order) {
        $logo = __DIR__ . "/../../../public/images/logo.png";
        $path = __DIR__ . "/../../../public/invoices/{$order->number}.pdf";

        $address = $order->address;
        $orderProducts = $order->orderProducts;
        $price = array_reduce($orderProducts, fn (float $r, OrderProduct $op) => $r + ($op->price * $op->quantity), 0);

        $invoice = new InvoicePrinter();
        $invoice->setLogo($logo);
        $invoice->setColor("#e0716d");
        $invoice->setType("Invoice");
        $invoice->setReference("   {$order->number}");
        $invoice->setDate(formatDate($order->payment->updatedAt, false));
        $invoice->setTo([$address->name, $order->email, $address->street, "{$address->postal}, {$address->city}", $address->country]);
        $invoice->hideToFromHeaders();
        $invoice->flipflop();

        foreach ($orderProducts as $orderProduct) {
            $size = $orderProduct->size;
            $product = $size->product;
            $promo = $product->price - $orderProduct->price;
            $invoice->addItem(
                $product->name, 
                "{$product->color} - {$size->size}", 
                $orderProduct->quantity, 
                $orderProduct->price * 0.2, 
                $product->price, 
                $promo, 
                $orderProduct->price * 1.2
            );
        }

        $invoice->setTotalsAlignment("horizontal");
        $invoice->addTotal("Subtotal", $price);
        $invoice->addTotal("VAT 20%", $price * 0.2);
        $invoice->addTotal("Total", $price * 1.2, true);

        $invoice->render($path, "F");

        return $path;
    }
}