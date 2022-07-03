<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateProductsTable {
    public function up() {
        Schema::create("products", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
            $table->float("price");
            $table->string("color");
            $table->float("promo")->nullable();
            $table->integer("fidelity");
            $table->integer("categoryId")->references("id")->on("categories");
            $table->integer("productId")->nullable()->references("id")->on("products");

            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("products");
    }
}