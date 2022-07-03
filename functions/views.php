<?php

use App\Helpers\Routers\Request;

function view(string $name, array $data = [], bool $get = false) {
    $files = getFiles("resources/views");
    
    $views = [];
    array_map(function ($file) use (&$views) {
        $path = str_replace("/", ".", preg_replace("/(^.*\/resources\/views\/)|(\.php$)/", "", $file));
        $views[$path] = $file;
    }, $files);

    if (!array_key_exists($name, $views)) {
        throw new Exception("View \"{$name}\" not found in \"app/resources/views\" directory.", 1);
    }

    $view = $views[$name];
    extract($data);

    $user = Request::getUser();
    $cartCount = Request::getCart()->count;
    
    ob_start();
    require $view;
    $buffer = ob_get_clean();
    
    if ($get) return $buffer;
    echo $buffer;
}