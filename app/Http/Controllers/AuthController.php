<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt\Hash;
use App\Helpers\Routers\Request;
use App\Helpers\Routers\Response;
use App\Mail\AuthConfirmation;
use App\Mail\ForgotPassword;
use App\Models\Access;
use App\Models\Cart;
use App\Models\Fidelity;
use App\Models\Reset;
use App\Models\Role;
use App\Models\User;
use Ramsey\Uuid\Uuid;

class AuthController {
    public function showLogin() {
        return view("auth.login");
    }
    public function login(Request $request) {
        $errors = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        if (!$errors) {
            $user = User::where("email", $request->body["email"])->first();
            if (!$user || !Hash::check($request->body["password"], $user->password)) {
                $errors = ["Email or password incorrect."];
            }
        }
        if ($errors) return Response::Json(400, $errors);

        if (isset($request->body["remember"]) && $request->body["remember"] === "on") {
            $access = Access::create([
                "token" => Uuid::uuid4(),
                "userId" => $user->id
            ]);
            addCookie("auth_id", $access->token);
        } else {
            setSession("auth_id", $user->id);
        }

        Response::JsonWithAlerts(200, [[
            "type" => "success",
            "message" => "Successfully connected."
        ]]);
    }

    public function showRegister() {
        return view("auth.register");
    }
    public function register(Request $request) {
        $errors = $request->validate([
            "username" => "required|username",
            "firstname" => "required",
            "lastname" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirm"
        ]);
        if (!$errors) {
            $role = Role::where("role", "USER")->first();
            if (!$role) $errors = ["No user role found."];
            else {
                $user = User::create([
                    "username" => $request->body["username"],
                    "email" => $request->body["email"],
                    "password" => Hash::make($request->body["password"]),
                    "firstname" => $request->body["firstname"],
                    "lastname" => $request->body["lastname"],
                    "roleId" => $role->id
                ]);
                Fidelity::create([
                    "points" => 0,
                    "userId" => $user->id
                ]);
            }
        }
        if ($errors) return Response::Json(400, $errors);
        
        $access = Access::create([
            "token" => Uuid::uuid4(),
            "userId" => $user->id
        ]);
        addCookie("auth_id", $access->token);

        $app = config("app")["name"];
        AuthConfirmation::with([
            "firstname" => $user->firstname,
            "lastname" => $user->lastname
        ])->subject("Welcome to {$app}")->send($user->email);

        Response::JsonWithAlerts(200, [[
            "type" => "success",
            "message" => "Account successfully created."
        ]]);
    }

    public function showForgot() {
        return view("auth.forgot");
    }
    public function forgot(Request $request) {
        $errors = $request->validate([
            "email" => "required|email",
        ]);
        if (!$errors) {
            $user = User::where("email", $request->body["email"])->first();
            if ($user) {
                $reset = Reset::create([
                    "token" => Uuid::uuid4(),
                    "userId" => $user->id
                ]);
                ForgotPassword::with([
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "url" => config("app")["url"] . "/auth/reset/" . $reset->token
                ])->subject("Reset your password")->send($user->email);
            }
        }
        if ($errors) return Response::Json(400, $errors);
        
        Response::Json(200, "An email has been sent to reset your password.");
    }

    public function showReset(Request $request) {
        $reset = Reset::where("token", $request->params["token"])->first();
        if (!$reset || !$reset->user) {
            return redirectWithAlerts("/", [[
                "type" => "error",
                "message" => "Token invalid or expired."
            ]]);
        }
        return view("auth.reset");
        
    }
    public function reset(Request $request) {
        $reset = Reset::where("token", $request->params["token"])->first();
        if (!$reset || !$user = $reset->user) {
            return redirectWithAlerts("/", [[
                "type" => "error",
                "message" => "Token invalid or expired."
            ]]);
        }

        $errors = $request->validate([
            "password" => "required|confirm"
        ]);
        if ($errors) return Response::Json(400, $errors);
        
        $user->password = Hash::make($request->body["password"]);
        $user->save();
        $reset->destroy();
        
        $access = Access::create([
            "token" => Uuid::uuid4(),
            "userId" => $user->id
        ]);
        addCookie("auth_id", $access->token);

        Response::JsonWithAlerts(200, [[
            "type" => "success",
            "message" => "Password successfully changed."
        ]]);
    }

    public function logout() {
        $access = Access::where("token", getCookie("auth_id") ?? "")->first();
        if ($access) {
            $access->destroy();
            deleteCookie("auth_id");
        }
        deleteSession("auth_id");
        redirect("/");
    }
}