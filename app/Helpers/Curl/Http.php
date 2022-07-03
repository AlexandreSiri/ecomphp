<?php

namespace App\Helpers\Curl;

use CurlHandle;

class Http {
    static function get(string $url, array $params = []) {
        return (new Request())->get($url, $params);
    }
    static function post(string $url, array $params = []) {
        return (new Request())->post($url, $params);
    }
    static function put(string $url, array $params = []) {
        return (new Request())->put($url, $params);
    }
    static function patch(string $url, array $params = []) {
        return (new Request())->patch($url, $params);
    }
    static function delete(string $url, array $params = []) {
        return (new Request())->delete($url, $params);
    }

    static function asForm() {
        return (new Request())->asForm();
    }
    static function withHeaders(array $headers) {
        return (new Request())->withHeaders($headers);
    }
    static function withUser(string $user) {
        return (new Request())->withUser($user);
    }
    static function accept(string $accept) {
        return (new Request())->accept($accept);
    }
    static function acceptJson() {
        return self::accept("application/json");
    }
    static function timeout(int $timeout) {
        return (new Request())->timeout($timeout);
    }
}