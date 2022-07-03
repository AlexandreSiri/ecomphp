<?php

namespace App\Helpers\Models;

use PDO;
use PDOException;

class Singleton {
    private static ?self $instance = null;
    private ?PDO $PDOInstance = null;

    private array $config;

    private function __construct() {
        $this->config = config("database");
        $host = $this->config["connection"]["host"];
        $port = $this->config["connection"]["port"];
        $database = $this->config["connection"]["database"];
        $username = $this->config["connection"]["username"];
        $password = $this->config["connection"]["password"];
        $charset = $this->config["connection"]["charset"];
        try {
            $this->PDOInstance = new PDO(
                "mysql:dbname=$database;charset=$charset;host=$host;port=$port",
                $username,
                $password
            );
            $this->PDOInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            dump($e->getMessage());
            $this->PDOInstance = null;
        }
    }

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new Singleton();
        }

        return self::$instance;
    }

    public function lastInsertId() {
        return $this->PDOInstance->lastInsertId();
    }

    public function query(string $q, array $params = []) {
        try {
            // dump($q);
            $query = $this->PDOInstance->prepare($q);
            $query->execute($params);
            return $query->fetchAll();
        } catch (PDOException $th) {
            dd($q, $th->getMessage());
        }

    }
}