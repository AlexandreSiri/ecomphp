<?php

namespace App\Helpers\Console;

use App\Helpers\Models\Singleton;
use Faker\Factory;

class Commands {
    public function serve() {
        exec("DEVELOPMENT=true php -S localhost:8080 -t public");
    }

    public function migrate($message = true) {
        $migrations = array_map(function (string $path) {
            $content = file_get_contents($path);
            preg_match("/(?<=class ).*(?= {)/", $content, $classname);
            preg_match("/(?<=::create\(\").*(?=\")/", $content, $table);
            preg_match_all("/(?<=on\(\").*(?=\"\))/", $content, $relations);

            $classname = array_shift($classname);
            $table = array_shift($table);
            $relations = array_shift($relations);
            
            require_once $path;
            $class = new $classname;

            return [
                "table" => $table,
                "relations" => $relations,
                "class" => $class
            ];
        }, getFiles("database/migrations"));

        array_map(fn (array $migration) => array_map(function (string $relation) use ($migrations) {
            $check = array_find(fn (array $m) => $m["table"] === $relation, $migrations);

            if (!$check) stop("Table \"$relation\" not found in \"database/migrations\" directory.", true);
        }, $migration["relations"]), $migrations);

        usort($migrations, function (array $a, array $b) {
            if (count($a["relations"]) === 0) return -1;
            else if (count($b["relations"]) === 0) return 1;
            else if (in_array($b["table"], $a["relations"])) return 1;
            return -1;
        });

        // foreach ($migrations as $key => $migration) {
        //     if (array_find(function ($relation) use ($migration) {
        //         if (in_array($relation["table"], $migration["relations"])) {
        //             $first = $migration["table"];
        //             $second = $relation["table"];
        //             stop("Relation loop between \"$first\" and \"$second\".", true);
        //         }
        //         return in_array($relation["table"], $migration["relations"]);

        //     }, array_slice($migrations, $key + 1))) {
        //         stop("test", true);
        //     }
        // };

        $pdo = Singleton::getInstance();
        $pdo->query("SET foreign_key_checks = 0;");

        array_map(fn (array $migration) => $migration["class"]->down(), $migrations);
        array_map(fn (array $migration) => $migration["class"]->up(), $migrations);
        
        $pdo->query("SET foreign_key_checks = 1;");
        
        $message && stop("Migration table created successfully.");
    }

    public function seed() {
        $this->migrate(false);

        $seeders = array_map(function (string $path) {
            $content = file_get_contents($path);
            preg_match("/(?<=class ).*(?= {)/", $content, $classname);
            preg_match("/(?<=::table\(\").*(?=\")/", $content, $table);

            $classname = array_shift($classname);
            $table = array_shift($table);
            
            require_once $path;
            $class = new $classname;

            return [
                "table" => $table,
                "class" => $class
            ];
        }, getFiles("database/seeders"));

        $pdo = Singleton::getInstance();
        $pdo->query("SET foreign_key_checks = 0;");

        $faker = Factory::create();

        array_map(fn (array $seeder) => $seeder["class"]->run($faker), $seeders);

        $pdo->query("SET foreign_key_checks = 1;");
        
        stop("Database seeding completed successfully.");
    }
    
    public function makeMigration(array $params) {
        $name = array_shift($params);
        if (!$name) stop("Please provide a name", true);

        $name = strtolower(preg_replace("/((?<=[a-z])[A-Z])/", '_${1}', lcfirst($name)));

        $classname = join("", array_map(fn (string $part) => ucfirst($part), explode("_", "create_{$name}_table")));
        $path = __DIR__ . "/../../../database/migrations/create_{$name}_table.php";

        file_put_contents($path, "<?php

use App\Helpers\Migrations\Blueprint;
use App\Helpers\Migrations\Schema;

class {$classname} {
    public function up() {
        Schema::create(\"$name\", function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }
    
    public function down() {
        Schema::dropIfExists(\"$name\");
    }
}");
        stop("Created Migration: \033[39mcreate_{$name}_table");
    }
    public function makeSeeder(array $params) {
        $name = array_shift($params);
        if (!$name) stop("Please provide a name", true);

        $name = strtolower(preg_replace("/((?<=[a-z])[A-Z])/", '_${1}', lcfirst($name)));

        $classname = join("", array_map(fn (string $part) => ucfirst($part), explode("_", "seeder_{$name}_table")));
        $path = __DIR__ . "/../../../database/seeders/seeder_{$name}_table.php";

        file_put_contents($path, "<?php

use App\Helpers\Seeders\DB;
use Faker\Generator;

class {$classname} {
    public function run(Generator \$faker) {
        DB::table(\"{$name}\")->insert([
        ]);
    }
}");
        stop("Created Seeder: \033[39mseeder_{$name}_table");
    }
}