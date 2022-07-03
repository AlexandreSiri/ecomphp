<?php

namespace App\Helpers\Migrations;

use App\Helpers\Models\Singleton;
use Closure;

abstract class Schema {
    static function create(string $table, Closure $func) {
        $structure = new Blueprint($table);
        call_user_func($func, $structure);
        $structure->checkForeign();
        $structure->checkPrimary();

        $pdo = Singleton::getInstance();
        $pdo->query($structure->getScript($table));
    }

    static function dropIfExists(string $table) {
        $pdo = Singleton::getInstance();
        $pdo->query("DROP TABLE IF EXISTS $table");
    }
}