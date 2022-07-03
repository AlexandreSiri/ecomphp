<?php

function getSession(string $key) {
    if (!isset($_SESSION) || !isset($_SESSION[$key])) return null;

    return $_SESSION[$key];
}

function setSession(string $key, $value) {
    if (!isset($_SESSION)) return;

    $_SESSION[$key] = $value;
}

function deleteSession(string $key) {
    if (!isset($_SESSION) || !isset($_SESSION[$key])) return;

    unset($_SESSION[$key]);
}

function getCookie(string $key) {
    if (!isset($_COOKIE) || !isset($_COOKIE[$key])) return null;

    return $_COOKIE[$key];
}

function addCookie(string $key, $value, int $expiration = 0) {
    if (!isset($_COOKIE)) return;
    
    setcookie($key, $value, $expiration ? time() + $expiration : 0, "/");
}

function deleteCookie(string $key) {
    if (!isset($_COOKIE) || !isset($_COOKIE[$key])) return;

    setcookie($key, null, -1, "/");
    unset($_COOKIE[$key]);
}