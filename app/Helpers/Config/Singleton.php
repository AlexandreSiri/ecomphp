<?php

namespace App\Helpers\Config;

class Singleton {
    private static ?self $instance = null;

    private array $config = [];

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new Singleton();
        }

        return self::$instance;
    }

    public function get(string $name) {
        if (isset($this->config[$name])) return $this->config[$name];
        return null;
    }
    public function set(string $name, mixed $value) {
        $this->config[$name] = $value;
    }
}