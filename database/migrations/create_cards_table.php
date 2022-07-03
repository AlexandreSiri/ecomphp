<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateCardsTable {
    public function up() {
        Schema::create("cards", function (Blueprint $table) {
            $table->id();
            $table->boolean("principal")->nullable();
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("cards");
    }
}