<?php

namespace App\Helpers\Routers;

class File {
    public function __construct(private string $tmp, private string $type, private string $name, private ?string $ext) {

    }

    public function getType() {
        return $this->type;
    }
    public function isImage() {
        return str_starts_with($this->type, "image/");
    }
    public function getName() {
        return $this->name;
    }
    public function moveTo(string $path) {
        $base = dirname(__DIR__, 3);
        if (!str_starts_with($path, "/")) $path = "/$path";

        dump($path);
        preg_match("/(?<=\/)((?!\/).)*\.[a-zA-Z]{0,4}$/", $path, $matches);
        $filename = array_shift($matches) ?? $this->name;
        $path = $base . str_replace($filename, "", $path);
        
        mkdir($path, 0777, true);
        move_uploaded_file($this->tmp, "{$path}{$filename}");
    }
}