<?php

namespace App\Helpers\Routers;

use App\Helpers\Models\Singleton;
use App\Models\Access;
use App\Models\Cart;
use App\Models\User;
use Exception;
use Ramsey\Uuid\Uuid;

class Request {
    public array $body = [];
    /** @var File[] */
    public array $files;
    public ?User $user = null;
    public Cart $cart;

    private array $rules;

    static function getUser() {
        if (getCookie("auth_id")) {
            $access = Access::where("token", getCookie("auth_id"))->first();
            if (!$access) deleteCookie("auth_id");
            else return $access->user;
        }
        if (getSession("auth_id")) return User::where("id", getSession("auth_id"))->first();
        else return null;
    }
    static function getCart() {
        $cart = null;
        if ($token = getCookie("cart_id")) {
            $cart = Cart::where("token", $token)->first();
        }
        if (!$cart) {
            $cart = Cart::create([
                "token" => Uuid::uuid4()
            ]);
        }
        if (!getCookie("cart_id") || getCookie("cart_id") !== $cart->token) {
            addCookie("cart_id", $cart->token);
        }
        return $cart;
    }
    
    public function __construct(public array $params) {
        $this->user = static::getUser();
        $this->cart = static::getCart();
        
        $this->body = array_replace([], $_POST);
        $this->files = $this->getFiles();

        $this->rules = [
            "required" => function ($value, $field, $params) {
                if ($value === null || !strlen($value)) return "The field \"{$field}\" is required.";
            },
            "email" => function ($value, $field, $params) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) return "The field \"{$field}\" need to be an email.";
            },
            "min" => function ($value, $field, $params) {
                $min = intval($params[0]);
                if (strlen($value) < $min) return "The field \"{$field}\" need to contains at least {$min} characters.";
            },
            "max" => function ($value, $field, $params) {
                $max = intval($params[0]);
                if (strlen($value) > $max) return "The field \"{$field}\" need to contains at most {$max} characters.";
            },
            "username" => function ($value, $field, $params) {
                if (!preg_match("/^[a-zA-Z0-9 _-]+$/", $value)) return "The field \"{$field}\" need to contains only letter, number and (space , _, -).";
            },
            "alphanumeric" => function ($value, $field, $params) {
                if (!preg_match("/^[a-zA-Z0-9]+$/", $value)) return "The field \"{$field}\" need to contains only letter and number.";
            },
            "digit" => function ($value, $field, $params) {
                if (!preg_match("/^\d+$/", $value)) return "The field \"{$field}\" need to be digit.";
            },
            "confirm" => function ($value, $field, $params) {
                $confirm = "confirm_$field";
                if (!isset($this->body[$confirm]) || $value !== $this->body[$confirm]) return "The field \"{$field}\" and \"{$confirm}\" need to be the same.";
            },
            "unique" => function ($value, $field, $params) {
                $table = $params[0];
                if (Singleton::getInstance()->query("SELECT * FROM {$table} WHERE {$field} = ?", [$value])) return "The field \"{$field}\" is already taken.";
            }
        ];
    }
    /** @return File[] */
    private function getFiles(): array {
        $files = [];
        foreach ($_FILES as $key => $value) {
            $tmp = $value["tmp_name"];
            $type = $value["type"];
            $name = $value["name"];
            preg_match("/\.[a-zA-Z]{0,4}$/", $name, $ext);
            
            $file = new File($tmp, $type, $name, array_pop($ext));
            $files[$key] = $file;
        }
        return $files;
    }

    public function validate(array $array): ?array {
        $errors = [];
        foreach ($array as $key => $rule) {
            $rules = array_map(function (string $rule) {
                $parts = explode(":", $rule);
                return [
                    "rule" => array_shift($parts),
                    "params" => array_map(function (string $param) {
                        if (preg_match("/^\d+(\.\d+)?$/", $param)) $param = floatval($param);
                        return $param;
                    }, $parts)
                ];
            }, explode("|", $rule));
            $value = $this->body[$key] = isset($this->body[$key]) ? $this->body[$key] : null;

            foreach ($rules as $r) {
                if (!isset($this->rules[$r["rule"]])) throw new Exception("Unknown rule \"{$r["rule"]}\".");
                $error = $this->rules[$r["rule"]]($value, $key, $r["params"]);
                
                if ($error) {
                    array_push($errors, $error); 
                    break;
                }
            }
        }

        return count($errors) ? $errors : null;
    }
}