<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateReviewsTable {
    public function up() {
        Schema::create("reviews", function (Blueprint $table) {
            $table->id();
            $table->string("content")->nullable();
            $table->integer("note");
            $table->integer("userId")->references("id")->on("users");
            $table->integer("productId")->references("id")->on("products");

            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("reviews");
    }
}