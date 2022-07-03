<?php

namespace App\Http\Middleware;

use App\Helpers\Routers\Request;
use App\Helpers\Routers\Response;

class Guest {
    public function handle(Request $request) {
        if ($request->user) return redirect("/");
        // Response::handleError("", 404);
    }
}