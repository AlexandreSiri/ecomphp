<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateOrderDetailsTable {
    public function up() {
        Schema::create("order_products", function (Blueprint $table) {
            $table->id();
            $table->integer("quantity");
            $table->float("price");
            $table->integer("sizeId")->references("id")->on("product_sizes");
            $table->integer("orderId")->references("id")->on("orders");
            $table->timestamps(true);
        });
    }
    
    public function down() {
        Schema::dropIfExists("order_products");
    }
}