<?php

function component(string $path, array $data = []) {
    extract($data);

    ob_start();
    require __DIR__ . "/../resources/components/{$path}.php";
    $buffer = ob_get_clean();
    return $buffer;
}

function svg(string $path) {
    ob_start();
    require __DIR__ . "/../resources/svgs/{$path}.svg";
    $buffer = ob_get_clean();
    return $buffer;
}

function head(?string $title = null) {
    $config = config("app");
    return component("header", ["title" => "{$config["name"]}" . ($title ? " - {$title}" : "")]);
}
function foot() {
    return component("footer");
}

function price(array $pr) {
    if (isset($pr["promo"])) {
        return "{$pr["promo"]}<s>{$pr["price"]}</s>";
    } else {
        return $pr["price"];
    }
}