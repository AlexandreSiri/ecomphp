<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Product extends Base {
    protected static bool $paranoid = true;
    
    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public string $color;
    public ?float $promo;
    public int $fidelity;
    
    public int $categoryId;
    public ?int $productId;
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function parent() {
        return $this->belongsTo(Product::class);
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function images() {
        return $this->hasMany(ProductImage::class);
    }
    public function sizes() {
        return $this->hasMany(ProductSize::class);
    }
    public function tags() {
        return $this->hasMany(Tag::class);
    }
    public function allReviews() {
        $product = $this->productId ? $this->parent : $this;
        $products = array_merge([$product], $product->products);

        $reviews = [];
        foreach ($products as $p) $reviews = array_merge($reviews, $p->reviews);
        
        return $reviews;
    }
    public function reviews() {
        return $this->hasMany(Review::class);
    }
}