<?php

use App\Core\App;
use App\Core\Database\Connection;
use App\Core\Database\QueryBuilder;

// Load Environment Variable from .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

App::bind("config", require "config.php");

App::bind("database", new QueryBuilder(
    Connection::make(App::get("config")["database"]))
);

function dd($value)
{
    var_dump($value);
    exit;
}

function view($name, $data = [])
{
    extract($data);
    return require "app/views/{$name}.view.php";
}

function redirect($path)
{
    $path = trim($path, "/");
    header("Location: /{$path}");
}

function env($key, $default = null)
{
    return $_ENV[$key] ? $_ENV[$key] : $default;
}
