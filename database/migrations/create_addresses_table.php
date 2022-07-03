<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateAddressesTable {
    public function up() {
        Schema::create("addresses", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("street");
            $table->string("postal");
            $table->string("city");
            $table->string("country");
            $table->boolean("principal")->nullable();
            $table->integer("userId")->nullable()->references("id")->on("users");

            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("addresses");
    }
}