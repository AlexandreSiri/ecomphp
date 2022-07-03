<?php

namespace App\Helpers\Routers;

class Response {
    static function handleError(string $views, int $status) {
        die("$status - $views");
    }
    static function Json(int $status, mixed $data) {
        http_response_code($status);
        header('Content-type: application/json');
        echo json_encode([
            "type" => $status >= 400 ? "error" : "success",
            "data" => $data
        ]);
    }
    static function JsonWithAlerts(int $status, array $alerts) {
        setSession("alerts", $alerts);
        return static::Json($status, $alerts);
    }
}