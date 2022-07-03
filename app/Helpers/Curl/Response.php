<?php

namespace App\Helpers\Curl;

use CurlHandle;

class Response {
    private int $status;
    private array $headers;
    private string $body;



    public function __construct(CurlHandle $curl, mixed $response) {
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $this->body = substr($response, $header_size);

        $this->getHeaders($header);
        $this->status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_reset($curl);
    }

    private function getHeaders(string $header) {
        array_map(function (string $line) {
            if (!str_contains($line, ":")) return;

            $parts = explode(":", $line);
            $this->headers[$parts[0]] = trim(str_replace("\r", "", join(":", array_slice($parts, 1))));
        }, explode("\n", $header));
    }

    public function body() {
        return $this->body;
    }
    public function json() {
        return json_decode($this->body, null, 512, JSON_THROW_ON_ERROR);
    }
    public function status() {
        return $this->status;
    }
    public function successful() {
        return $this->status >= 200 && $this->status < 300;
    }
    public function failed() {
        return $this->status === 0 || $this->status >= 400;
    }
    public function header(string $header) {
        if (!array_key_exists($header, $this->headers)) return null;
        return $this->headers[$header];
    }
    public function headers() {
        return $this->headers;
    }


}