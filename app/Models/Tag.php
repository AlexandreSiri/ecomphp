<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Tag extends Base {
    public int $id;
    public string $name;
    
    public int $productId;
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
}