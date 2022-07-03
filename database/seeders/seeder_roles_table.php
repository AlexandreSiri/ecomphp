<?php

use App\Helpers\Seeders\DB;
use Faker\Generator;

class SeederRoleTable {
    public function run(Generator $faker) {
        DB::table("roles")->insert([
            "role" => "ADMINISTRATOR",
            "label" => "Administrateur"
        ]);
        DB::table("roles")->insert([
            "role" => "USER",
            "label" => "Utilisateur"
        ]);
    }
}