<?php

namespace App\Helpers\Curl;

use CurlHandle;

class Request
{
    private CurlHandle $curl;
    private bool $asForm = false;
    private array $headers = [];
    private string $accept = "*/*";
    private int $timeout = 5;
    private string $user;

    public function __construct()
    {
        $this->curl = Singleton::getInstance()->getCurl();
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_HEADER => true
        ];
        curl_setopt_array($this->curl, $options);
    }

    private function getUrl(string $url, array $params = [])
    {
        preg_match("/(?<=\\?).*/", $url, $query);
        $query = join("&", array_filter([array_shift($query), http_build_query($params)], fn ($part) => !!strlen($part)));

        return preg_replace("/\\?.*/", "", $url) . (strlen($query) ? "?$query" : "");
    }

    public function asForm()
    {
        $this->asForm = true;
        return $this;
    }
    public function withHeaders(array $headers)
    {
        $this->headers = array_map(fn ($value, $header) => "{$header}: {$value}", $headers, array_keys($headers));
        return $this;
    }
    public function withUser(string $user)
    {
        $this->user = $user;
        return $this;
    }
    public function accept(string $accept)
    {
        $this->accept = $accept;
        return $this;
    }
    public function acceptJson()
    {
        $this->accept = "application/json";
        return $this;
    }
    public function timeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    public function get(string $url, array $params = [])
    {
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $this->getUrl($url, $params),
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array_merge($this->headers, [
                "Content-Type: " . ($this->asForm ? "application/x-www-form-urlencoded" : "application/json"),
                "accept:" . $this->accept
            ]),
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_USERPWD => $this->user ?? ""
        ]);

        return new Response($this->curl, curl_exec($this->curl));
    }
    public function post(string $url, array $params = [])
    {
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->asForm ? http_build_query($params) : json_encode($params, JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => array_merge($this->headers, [
                "Content-Type: " . ($this->asForm ? "application/x-www-form-urlencoded" : "application/json"),
                "accept:" . $this->accept
            ]),
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_USERPWD => $this->user ?? ""
        ]);

        return new Response($this->curl, curl_exec($this->curl));
    }
    public function put(string $url, array $params = [])
    {
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => $this->asForm ? http_build_query($params) : json_encode($params, JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => array_merge($this->headers, [
                "Content-Type: " . ($this->asForm ? "application/x-www-form-urlencoded" : "application/json"),
                "accept:" . $this->accept
            ]),
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_USERPWD => $this->user ?? ""
        ]);

        return new Response($this->curl, curl_exec($this->curl));
    }
    public function patch(string $url, array $params = [])
    {
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => $this->asForm ? http_build_query($params) : json_encode($params, JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER => array_merge($this->headers, [
                "Content-Type: " . ($this->asForm ? "application/x-www-form-urlencoded" : "application/json"),
                "accept:" . $this->accept
            ]),
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_USERPWD => $this->user ?? ""
        ]);

        return new Response($this->curl, curl_exec($this->curl));
    }
    public function delete(string $url, array $params = [])
    {
        curl_setopt_array($this->curl, [
            CURLOPT_URL => $this->getUrl($url, $params),
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array_merge($this->headers, [
                "Content-Type: " . ($this->asForm ? "application/x-www-form-urlencoded" : "application/json"),
                "accept:" . $this->accept
            ]),
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_USERPWD => $this->user ?? ""
        ]);

        return new Response($this->curl, curl_exec($this->curl));
    }
}
