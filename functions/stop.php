<?php

function stop(string $message = "", bool $error = false) {
    $color = $error ? "\033[31m" : "\033[32m";
    echo "{$color}{$message}\n";
    exit();
}