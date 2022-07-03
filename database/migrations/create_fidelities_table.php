<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateFidelitiesTable {
    public function up() {
        Schema::create("fidelities", function (Blueprint $table) {
            $table->id();
            $table->integer("points");
            $table->integer("userId")->references("id")->on("users");

            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("fidelities");
    }
}