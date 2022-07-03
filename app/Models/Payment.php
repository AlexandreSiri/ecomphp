<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Payment extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public string $token;
    public string $status;

    public int $orderId;

    public function order() {
        return $this->belongsTo(Order::class);
    }
}