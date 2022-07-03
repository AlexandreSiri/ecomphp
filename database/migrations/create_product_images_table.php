<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateProductImagesTable {
    public function up() {
        Schema::create("product_images", function (Blueprint $table) {
            $table->id();
            $table->string("source");
            $table->integer("productId")->references("id")->on("products");
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("product_images");
    }
}