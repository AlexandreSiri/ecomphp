<?php

use App\Helpers\Routers\Request;
use App\Kernel;

$GLOBALS["routes.web"] = [];


class Route {
    private string $path;
    private string | null $name = null;
    
    private string $callback;
    private array $params = [];

    public array $middlewares = [];

    public function __construct(string $path, private string $method, string $callback) {
        $parts = explode("@", $callback);
        $file = $parts[0];
        $call = $parts[1] ?? "Index";

        $reflection = new ReflectionClass("\App\Http\Controllers\\{$file}");
        if (!$reflection->hasMethod($call)) throw new Exception("Method \"{$call}\" does not exist", 1);

        array_map(function (string $p) {
            if (!strlen($p) || !str_starts_with($p, "{") || !str_ends_with($p, "}")) return;
            $optional = preg_match("/\?/", $p);
            $param = str_replace(["{", "}", "?"], "", $p);
            $this->params[$param] = ["/^.*$/", !$optional];
        }, explode("/", $path));

        $test = [];
        preg_match("/\?[^}]/", $path, $test);
        if (count($test) > 0) {
            throw new Exception("Path \"$path\" can't contains query parameters.", 1);
        }

        $this->path = $path;
        $this->callback = $callback;
    }

    public function getPath() {
        return $this->path;
    }
    public function getMethod() {
        return $this->method;
    }
    public function getName() {
        return $this->name;
    }
    public function getParam(string $name): array {
        return $this->params[$name];
    }
    public function getParams(): array {
        return array_keys($this->params);
    }
    public function getNecessaryParam(): array {
        return array_keys(array_filter($this->params, fn($param) => $param[1]));
    }
    public function where(string $param, string $regex): self {
        $regex = "/^$regex$/";
        if (!array_key_exists($param, $this->params)) {
            throw new Exception("Param \"$param\" doesn't exist in the route \"$this->path\".", 1);
        }
        if (@preg_match($regex, "") === false) {
            throw new Exception(error_get_last()["message"], 1);
        }
        $this->params[$param][0] = $regex;
        return $this;
    }
    public function middleware(string|array $middlewares): self {
        $middlewares = gettype($middlewares) === "array" ? $middlewares : [$middlewares];
        $this->middlewares = array_map(function (string $m) {
            if (!array_key_exists($m, Kernel::$routeMiddleware)) {
                throw new Exception("Middleware \"$m\" doesn't exist.", 1);
            }
            return Kernel::$routeMiddleware[$m];
        }, $middlewares);

        return $this;
    }
    public function name(string $name): self {
        $this->name = $name;
        return $this;
    }
    
    public function run(Request $request) {
        $parts = explode("@", $this->callback);
        $file = $parts[0];
        $method = $parts[1] ?? "Index";

        $reflection = new ReflectionClass("\App\Http\Controllers\\{$file}");
        $instance = $reflection->newInstance();
        $callback = $reflection->getMethod($method);
        $callback->invoke($instance, $request);
    }
}

class Router {
    static function get(string $path, string $callback): Route {
        $route = new Route($path, "GET", $callback);
        array_push($GLOBALS["routes.web"], $route);
        return $route;
    }
    static function post(string $path, string $callback): Route {
        $route = new Route($path, "POST", $callback);
        array_push($GLOBALS["routes.web"], $route);
        return $route;
    }
    static function put(string $path, string $callback): Route {
        $route = new Route($path, "PUT", $callback);
        array_push($GLOBALS["routes.web"], $route);
        return $route;
    }
    static function delete(string $path, string $callback): Route {
        $route = new Route($path, "DELETE", $callback);
        array_push($GLOBALS["routes.web"], $route);
        return $route;
    }

    private static function getRoute(): array {
        $uri = urldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );
        $method = $_SERVER["REQUEST_METHOD"];

        $params = [];

        $routes = array_filter($GLOBALS["routes.web"], function (Route $route) use ($uri, $method, &$params) {
            $uri_parts = explode("/", $uri);
            $route_parts = explode("/", $route->getPath());

            if ($method !== $route->getMethod()) return false;
            $index = -1;
            foreach ($uri_parts as $i => $_) {
                $index++;
                $part = $uri_parts[$index];
                if (!key_exists($i, $route_parts)) return false;
                $route_part = $route_parts[$i];
                if (!str_starts_with($route_part, "{") && !str_ends_with($route_part, "}") && $part !== $route_part) return false;
                if (str_starts_with($route_part, "{") && str_ends_with($route_part, "}")) {
                    $param = str_replace(["{", "}", "?"], "", $route_part);
                    [$regex, $required] = $route->getParam($param);
                    if (!preg_match($regex, $part) && $required) return false;
                    else if (!preg_match($regex, $part) && !$required) $index--;
                    else if (preg_match($regex, $part)) {
                        $params[$param] = $part;
                    }
                }
            }
            $checkParams = true;
            array_map(function (string $key) use (&$params, &$checkParams, $route) {
                foreach ($route->getNecessaryParam() as $key) {
                    if (!array_key_exists($key, $params)) return $checkParams = false;
                }
                
                if (array_key_exists($key, $params)) return;
                $params[$key] = null;
            }, $route->getParams());

            return $checkParams;
        });


        return [count((array) $routes) > 0 ? array_shift($routes) : null, $params];
    }

    static function getRouteFromName(string $name): Route | null {
        foreach ($GLOBALS["routes.web"] as $value) {
            if ($value->getName() === $name) return $value;
        }
        return null;
    }

    static function run() {
        [$route, $params] = Router::getRoute();
        if ($route === null) return view("errors.404");
        $request = new Request($params);


        array_map(function ($middleware) use ($request) {
            $class = new $middleware;
            $class->handle($request);
        }, $route->middlewares);

        $route->run($request);
    }
}
