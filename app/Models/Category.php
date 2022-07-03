<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Category extends Base {
    public int $id;
    public string $name;    
    public ?int $categoryId;
    
    public function categories() {
        return $this->hasMany(Category::class);
    }
    public function parent() {
        return $this->belongsTo(Category::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }
}