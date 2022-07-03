<?php

use App\Helpers\Seeders\DB;
use Faker\Generator;

class SeederProductsTable {
    public function run(Generator $faker) {
        $sizes = [39, 39.5, 40, 40.5, 41, 41.5, 42, 42.5, 43, 43.5, 44, 44.5, 45];
        
        $productId = null;
        foreach (["White", "Black", "Red"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Nike Air Zoom SuperRep 2",
                "description" => $faker->realText(),
                "price" => 120,
                "color" => $color,
                "fidelity" => 120,
                "categoryId" => 2,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["Blue", "Green"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Nike Mercurial Vapor 14 Elite FG",
                "description" => $faker->realText(),
                "price" => 250,
                "promo" => 180,
                "color" => $color,
                "fidelity" => 250,
                "categoryId" => 3,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["White", "Purple"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Nike React Vapor Ultrafly Elite 4",
                "description" => $faker->realText(),
                "price" => 100,
                "color" => $color,
                "fidelity" => 100,
                "categoryId" => 4,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["Blue"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Air Jordan 1 Low",
                "description" => $faker->realText(),
                "price" => 110,
                "color" => $color,
                "fidelity" => 110,
                "categoryId" => 5,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["White", "Black"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Nike Air Force 1",
                "description" => $faker->realText(),
                "price" => 100,
                "color" => $color,
                "fidelity" => 100,
                "categoryId" => 6,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["White", "Brown"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Adidas Ultraboost 21 Tokyo",
                "description" => $faker->realText(),
                "price" => 180,
                "promo" => 120,
                "color" => $color,
                "fidelity" => 180,
                "categoryId" => 7,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["White", "Black"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Adidas Ultraboost 4.0 DNA",
                "description" => $faker->realText(),
                "price" => 160,
                "color" => $color,
                "fidelity" => 160,
                "categoryId" => 7,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["Black"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Adidas Ultraboost 22 Y3",
                "description" => $faker->realText(),
                "price" => 280,
                "color" => $color,
                "fidelity" => 280,
                "categoryId" => 7,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["Blue", "Yellow"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Adidas Ultraboost 5.0 DNA",
                "description" => $faker->realText(),
                "price" => 160,
                "color" => $color,
                "fidelity" => 160,
                "categoryId" => 7,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["Green", "Black"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "Adidas Ultraboost 21 Parley",
                "description" => $faker->realText(),
                "price" => 200,
                "promo" => 160,
                "color" => $color,
                "fidelity" => 200,
                "categoryId" => 7,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }

        $productId = null;
        foreach (["White"] as $color) {
            $id = DB::table("products")->insert([
                "name" => "NikeCourt React Vapor NXT",
                "description" => $faker->realText(),
                "price" => 180,
                "color" => $color,
                "fidelity" => 180,
                "categoryId" => 1,
                "productId" => $productId
            ]);
            if (!$productId) $productId = $id;
            for ($i=1; $i <= 3; $i++) { 
                DB::table("product_images")->insert([
                    "source" => "/images/products/{$id}/{$i}.png",
                    "productId" => $id
                ]);
            }
            foreach ($faker->randomElements($sizes, $faker->numberBetween(3, 10)) as $size) {
                DB::table("product_sizes")->insert([
                    "size" => $size,
                    "quantity" => $faker->numberBetween(0, 4),
                    "productId" => $id
                ]);
            }
        }
    }
}