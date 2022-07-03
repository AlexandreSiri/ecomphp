<?php

namespace App\Helpers\Seeders;

use App\Helpers\Models\Singleton;
use DateTime;

class DB {
    private bool $timestamp = true;
    private function __construct(private string $table) {}

    public static function table(string $table) {
        return new DB($table);
    }

    public function withoutTimestamp() {
        $this->timestamp = false;
        return $this;
    }
    public function insert(array $data) {
        $where = [];
        $values = [];
        foreach (array_merge($data, $this->timestamp ? [
            "createdAt" => formatDate(new DateTime()),
            "updatedAt" => formatDate(new DateTime())
        ] : []) as $key => $value) {
            array_push($where, $key);
            array_push($values, $value);
        }

        $fields = join(", ", $where);
        $valuesString = join(", ", array_map(fn () => "?", $values));
        $query = "INSERT INTO {$this->table} ({$fields}) VALUES ($valuesString)";

        Singleton::getInstance()->query($query, $values);
        return Singleton::getInstance()->lastInsertId();
    } 
}