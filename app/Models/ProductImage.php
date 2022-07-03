<?php

namespace App\Models;

use App\Helpers\Models\Base;

class ProductImage extends Base {
    public int $id;
    public string $source;
    
    public int $productId;
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}