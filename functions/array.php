<?php

function array_find(callable $call, array $array) {
    $clone = array_filter($array, $call);
    return array_shift($clone);
}