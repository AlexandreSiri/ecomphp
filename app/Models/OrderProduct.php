<?php

namespace App\Models;

use App\Helpers\Models\Base;

class OrderProduct extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public int $quantity;
    public float $price;
    public int $sizeId;
    public int $orderId;

    public function size() {
        return $this->belongsTo(ProductSize::class, [
            "id" => $this->sizeId
        ]);
    }
    public function order() {
        return $this->belongsTo(Order::class);
    }
}