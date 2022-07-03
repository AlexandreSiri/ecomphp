<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateUserResetsTable {
    public function up() {
        Schema::create("resets", function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->integer("userId")->references("id")->on("users");
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("resets");
    }
}