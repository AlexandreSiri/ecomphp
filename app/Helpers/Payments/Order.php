<?php

namespace App\Helpers\Payments;

use App\Helpers\Routers\Request;
use App\Models\Address;
use App\Models\Order as ModelsOrder;
use App\Models\OrderProduct;

class Order {
    static function generateOrderId() {
        $orderId = substr(str_shuffle(str_repeat("0123456789", 100)), 0, 16);
        $orderId = join("-", [substr($orderId, 0, 4), substr($orderId, 4, 6), substr($orderId, 10, 6)]);
        if (ModelsOrder::where("number", $orderId)->first()) return static::generateOrderId();
        
        return $orderId;
    }
    
    static function create(array $products, Address $address, string $email) {
        $user = Request::getUser();
        $order = ModelsOrder::create([
            "number" => static::generateOrderId(),
            "email" => $email,
            "userId" => $user ? $user->id : null,
            "addressId" => $address->id
        ]);

        foreach ($products as $row) {
            OrderProduct::create([
                "quantity" => $row["count"],
                "price" => $row["product"]["promo"] ?? $row["product"]["price"],
                "sizeId" => $row["product"]["id"],
                "orderId" => $order->id
            ]);
        }
        return $order;
    }
}