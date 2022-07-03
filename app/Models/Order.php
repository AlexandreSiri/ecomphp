<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Order extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public string $number;
    public string $email;
    public ?int $userId;
    public int $addressId;

    public function sizes() {
        $oProducts = $this->orderProducts()->run();
        return array_map(fn (OrderProduct $o) => $o->size, $oProducts);
    }
    public function address() {
        return $this->belongsTo(Address::class);
    }
    public function payment() {
        return $this->hasOne(Payment::class);
    }
    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }
}