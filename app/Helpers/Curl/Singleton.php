<?php

namespace App\Helpers\Curl;

use CurlHandle;

class Singleton {
    private static ?self $instance = null;
    private CurlHandle $curl;

    private function __construct() {
        $this->curl = curl_init();
    }

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new Singleton();
        }

        return self::$instance;
    }

    public function getCurl() {
        return $this->curl;
    }
}