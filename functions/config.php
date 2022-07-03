<?php

use App\Helpers\Config\Singleton;

function config(string $name) {
    $config = Singleton::getInstance()->get($name);
    if ($config) return $config;
    $files = array_filter(getFiles("config"), function (string $path) use ($name) {
        $parts = explode("/", $path);
        $file = preg_replace("/(\\.[a-z]+)$/", "", array_pop($parts));
        
        return $file === $name;
    });
    $file = array_shift($files);

    if ($file === null) throw new Exception("File \"{$name}.php\" not found in \"config\" directory.", 1);
    $config = require_once $file;
    Singleton::getInstance()->set($name, $config);
    return $config;
}

function env(string $key, mixed $default = null) {
    $value = getenv($key);
    if (!$value) return $default;
    return match (strtolower($value)) {
        'true', '(true)' => true,
        'false', '(false)' => false,
        'empty', '(empty)' => '',
        'null', '(null)' => null,
        default => strlen($value) ? $value : $default,
    };
}
