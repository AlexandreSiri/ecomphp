<?php

use App\Helpers\Seeders\DB;
use App\Models\Product;
use Faker\Generator;

class SeederReviewsTable {
    public function run(Generator $faker) {
        $products = Product::all();

        foreach ($products as $product) {
            for ($i=0; $i < $faker->numberBetween(5, 10); $i++) {
                DB::table("reviews")->insert([
                    "content" => $faker->numberBetween(0, 2) ? null : $faker->realText(),
                    "note" => $faker->numberBetween(3, 5),
                    "userId" => $faker->numberBetween(1, 51),
                    "productId" => $product->id
                ]);
            }
        }
    }
}