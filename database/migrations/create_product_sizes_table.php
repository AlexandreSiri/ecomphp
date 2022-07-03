<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateProductSizesTable {
    public function up() {
        Schema::create("product_sizes", function (Blueprint $table) {
            $table->id();
            $table->float("size");
            $table->integer("quantity");
            $table->integer("productId")->references("id")->on("products");

            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("product_sizes");
    }
}