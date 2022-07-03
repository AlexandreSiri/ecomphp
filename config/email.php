<?php

return [
    "from" => [
        "name" => env("EMAIL_DEFAULT_NAME"),
        "email" => env("EMAIL_DEFAULT_EMAIL")
    ],
    "mailjet" => [
        "client" => env("EMAIL_KEY_CLIENT"),
        "secret" => env("EMAIL_KEY_SECRET")
    ]
];
