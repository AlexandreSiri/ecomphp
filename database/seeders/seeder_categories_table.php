<?php

use App\Helpers\Seeders\DB;
use Faker\Generator;

class SeederCategoriesTable {
    public function run(Generator $faker) {
        $sportId = DB::table("categories")->insert([
            "name" => "Sport",
        ]);
        DB::table("categories")->insert([
            "name" => "Training & fitness",
            "categoryId" => $sportId
        ]);
        DB::table("categories")->insert([
            "name" => "Football",
            "categoryId" => $sportId
        ]);
        DB::table("categories")->insert([
            "name" => "Basketball",
            "categoryId" => $sportId
        ]);

        DB::table("categories")->insert([
            "name" => "Jordan"
        ]);
        DB::table("categories")->insert([
            "name" => "Airforce"
        ]);
        DB::table("categories")->insert([
            "name" => "Ultraboost"
        ]);

        // DB::table("categories")->insert([
        //     "name" => "Skateboard",
        //     "categoryId" => $transportId
        // ]);
    }
}