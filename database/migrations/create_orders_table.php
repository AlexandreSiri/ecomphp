<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateOrdersTable {
    public function up() {
        Schema::create("orders", function (Blueprint $table) {
            $table->id();
            $table->string("number")->unique();
            $table->string("email");
            $table->integer("userId")->nullable()->references("id")->on("users");
            $table->integer("addressId")->references("id")->on("addresses");
            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("orders");
    }
}