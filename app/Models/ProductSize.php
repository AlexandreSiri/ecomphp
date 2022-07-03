<?php

namespace App\Models;

use App\Helpers\Models\Base;

class ProductSize extends Base {
    protected static bool $paranoid = true;

    public int $id;
    public float $size;
    public int $quantity;
    
    public int $productId;
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}