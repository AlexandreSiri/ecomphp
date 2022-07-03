<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateRolesTable {
    public function up() {
        Schema::create("roles", function (Blueprint $table) {
            $table->id();
            $table->string("role");
            $table->string("label");
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("roles");
    }
}