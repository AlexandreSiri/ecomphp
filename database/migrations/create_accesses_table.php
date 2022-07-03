<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateAccessesTable {
    public function up() {
        Schema::create("accesses", function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->integer("userId")->references("id")->on("users");
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("accesses");
    }
}