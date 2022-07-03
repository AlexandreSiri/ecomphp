<?php

namespace App\Http\Middleware;

use App\Helpers\Routers\Request;
use App\Helpers\Routers\Response;

class Authentificate {
    public function handle(Request $request) {
        if (!$request->user) {
            redirect(route("auth.login.show"));
            die();
        }
    }
}