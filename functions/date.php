<?php

function formatDate(DateTime $date, bool $full = true): string {
    return $full ? date_format($date, "Y-m-d H:i:s.v") : date_format($date, "Y-m-d");
}