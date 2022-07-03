<!-- <?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class CreateCartsTable {
    public function up() {
        Schema::create("carts", function (Blueprint $table) {
            $table->id();
            $table->string("token");
            
            $table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists("carts");
    }
} 