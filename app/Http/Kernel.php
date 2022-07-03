<?php

namespace App;

use App\Http\Middleware\Authentificate;
use App\Http\Middleware\Guest;

class Kernel {
    static $routeMiddleware = [
        "guest" => Guest::class,
        "auth" => Authentificate::class
    ];
}
