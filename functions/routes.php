<?php

function route(string $name, array $param = []): string | null {
    $route = Router::getRouteFromName($name);
    if ($route === null) return null;

    $needsParams = $route->getNecessaryParam();
    $paramsList = join(", ", array_filter($needsParams, function ($p) use ($param) {
        if (key_exists($p, $param)) return false;
        return true;
    }));
    if (count($needsParams) > count($param)) {
        throw new Exception("Route \"{$name}\" needs \"$paramsList\" param(s)", 1);
    }

    $uri = $route->getPath();
    foreach ($param as $key => $value) {
        $uri = preg_replace("/\{$key(\?)?\}/", urlencode($value), $uri);
    }
    $uri = preg_replace("/\{.*?\}/", "", $uri);
    $uri = preg_replace("/\/\//", "/", $uri);
    $uri = preg_replace("/\/$/", "", $uri);


    return $uri;
}

function redirect(string $url) {
    header("Location: $url");
}
function redirectWithAlerts(string $url, array $alerts) {
    setSession("alerts", $alerts);
    redirect($url);
}