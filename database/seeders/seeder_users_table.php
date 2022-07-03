<?php

use App\Helpers\Crypt\Hash;
use App\Helpers\Seeders\DB;
use Faker\Generator;

class SeedUsersTable {
    public function run(Generator $faker) {
        $userId = DB::table("users")->insert([
            "username" => "Maxou le boss",
            "email" => "bey.maximilien@gmail.com",
            "password" => Hash::make("luna1307"),
            "firstname" => "Maximilien",
            "lastname" => "BEY",
            "roleId" => 1
        ]);
        DB::table("fidelities")->insert([
            "points" => 0,
            "userId" => $userId
        ]);

        for ($j=0; $j < 3; $j++) { 
            DB::table("addresses")->insert([
                "name" => $faker->lastName(),
                "street" => $faker->streetAddress(),
                "postal" => $faker->postcode(),
                "city" => $faker->city(),
                "country" => $faker->country(),
                "principal" => $j === 0 ? 1 : null,
                "userId" => $userId
            ]);
        }

        for ($i=0; $i < 50; $i++) {
            $userId = DB::table("users")->insert([
                "username" => $faker->userName(),
                "email" => $faker->email(),
                "password" => Hash::make("luna1307"),
                "firstname" => $faker->firstName(),
                "lastname" => $faker->lastName(),
                "roleId" => $i < 5 ? 1 : 2
            ]);
            DB::table("fidelities")->insert([
                "points" => 0,
                "userId" => $userId
            ]);

            for ($j=0; $j < 3; $j++) { 
                DB::table("addresses")->insert([
                    "name" => $faker->firstName() . " " . $faker->lastName(),
                    "street" => $faker->streetAddress(),
                    "postal" => $faker->postcode(),
                    "city" => $faker->city(),
                    "country" => $faker->country(),
                    "principal" => $j === 0 ? 1 : null,
                    "userId" => $userId
                ]);
            }
        }
    }
}