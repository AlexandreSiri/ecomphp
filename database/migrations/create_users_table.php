<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateUsersTable {
    public function up() {
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("username");
            $table->string("email")->unique();
            $table->string("password");
            $table->string("firstname");
            $table->string("lastname");
            $table->integer("roleId")->references("id")->on("roles");

            $table->date("birthAt")->nullable();
            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("users");
    }
}