<?php

namespace App\Helpers\Crypt;

class Hash {
    static function make(string $password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    static function check(string $password, string $hash) {
        return password_verify($password, $hash);
    }
}