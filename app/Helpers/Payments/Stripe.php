<?php

namespace App\Helpers\Payments;

use App\Helpers\Curl\Http;
use App\Models\Order;
use App\Models\Payment;

class Stripe {
    private static function getPrivate() {
        return config("stripe")["private"];
    }
    private static function getPublic() {
        return config("stripe")["public"];
    }

    static function getSession(Payment $payment) {
        $response = Http::withUser(static::getPrivate())->get("https://api.stripe.com/v1/checkout/sessions/{$payment->token}");
        return $response->successful() ? $response->json() : null;
    }

    static function createSession(array $products, Order $order, string $email) {
        $url = config("app")["url"];
        $response = Http::withUser(static::getPrivate())->asForm()->post("https://api.stripe.com/v1/checkout/sessions", [
            "success_url" => $url . route("payment.confirm", ["id" => $order->number]),
            "cancel_url" => $url . route("payment.cancel", ["id" => $order->number]),
            "customer_email" => $email,
            "line_items" => array_map(fn (array $row) => [
                "price_data" => [
                    "currency" => "USD",
                    "product_data" => [
                        "name" => $row["product"]["name"],
                        "images" => [
                            preg_replace("/\\+/", "", config("app")["url"] . $row["product"]["image"])
                        ]
                    ],
                    "unit_amount" => ($row["product"]["promo"] ?? $row["product"]["price"]) * 100 * 1.2,
                    "tax_behavior" => "inclusive"
                ],
                "quantity" => $row["count"],
            ], $products),
            "mode" => "payment",
        ]);
        
        return $response->successful() ? $response->json() : null;
    }
}