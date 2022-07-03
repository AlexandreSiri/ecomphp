<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateCartProductsTable {
    public function up() {
        Schema::create("cart_products", function (Blueprint $table) {
            $table->id();
            $table->integer("cartId")->references("id")->on("carts");
            $table->integer("sizeId")->references("id")->on("product_sizes");

            // $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("cart_products");
    }
}