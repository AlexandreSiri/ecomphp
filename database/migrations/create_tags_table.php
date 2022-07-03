<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateTagsTable {
    public function up() {
        Schema::create("tags", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("productId")->references("id")->on("products");
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("tags");
    }
}