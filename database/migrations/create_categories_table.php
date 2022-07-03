<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateCategoriesTable {
    public function up() {
        Schema::create("categories", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("categoryId")->nullable()->references("id")->on("categories");
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("categories");
    }
}