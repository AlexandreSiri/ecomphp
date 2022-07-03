<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreatePaymentsTable {
    public function up() {
        Schema::create("payments", function (Blueprint $table) {
            $table->id();
            $table->string("token");
            $table->string("status");
            $table->integer("orderId")->references("id")->on("orders");
            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("payments");
    }
}