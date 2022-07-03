<?php

namespace App\Models;

use App\Helpers\Models\Base;

class Cart extends Base {
    public int $id;
    public string $token;
    
    // public function products() {
    //     $products = [];
    //     foreach ($this->hasManyThrough(ProductSize::class, "cart_products")->run() as $product) {
    //         $index = -1;
    //         foreach ($products as $i => $p) if ($p["product"]->id === $product->id) {
    //             $index = $i;
    //             break;
    //         }
    //         if ($index >= 0) $products[$index]["count"]++;
    //         else array_push($products, [
    //             "product" => $product,
    //             "count" => 1
    //         ]);
    //     }

    //     return $products;
    // }
    
    public function sizes() {
        return $this->hasManyThrough(ProductSize::class, "cart_products", [
            "cartId" => $this->id
        ], [
            "sizeId" => "id"
        ]);
    }
    public function count() {
        return count($this->sizes()->run());
    }
    public function addSize(ProductSize $size) {
        return $this->addThrough($size, "cart_products", [
            "cartId" => $this->id
        ], [
            "sizeId" => $size->id
        ]);
    }
    public function removeSize(ProductSize $size, bool $all = false) {
        return $this->removeTrough($size, "cart_products", [
            "cartId" => $this->id
        ], [
            "sizeId" => $size->id
        ], $all);
    }
    public function removeAllSize() {
        foreach ($this->sizes()->run() as $size) $this->removeSize($size, true);
    }
}