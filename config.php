<?php

return [
    "database" => [
        "name" => env("DATABASE_NAME"),
        "username" => env("DATABASE_USERNAME"),
        "password" => env("DATABASE_PASSWORD"),
        "connection" => env("DATABASE_CONNECTION"),
        "options" => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],
];
