<?php

namespace App\Core;

use Exception;

class Router
{
    public $routes = [
        "GET" => [],
        "POST" => [],
        "PATCH" => [],
        "PUT" => [],
        "DELETE" => [],
    ];

    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    public function direct($uri, $requestMethod)
    {

        $routes = $this->routes[$requestMethod];

        if (array_key_exists($uri, $routes)) {
            return $this->callAction(
                ...explode("@", $routes[$uri]),
            );

        } elseif (array_key_exists($uri, $this->routes["GET"])) {
            throw new Exception("Method not allowed", 405);
        }

        throw new Exception("No Route Define for this URI", 404);
    }

    protected function callAction($controller, $method)
    {
        $controller = "App\\Controllers\\{$controller}";
        $instance = new $controller;

        if (!method_exists($instance, $method)) {
            throw new Exception("{$controller} does not respond to the {$method} method", 1);
        }

        return $instance->$method();
    }

    public function get($uri, $controller)
    {
        $this->routes["GET"][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes["POST"][$uri] = $controller;
    }

    public function patch($uri, $controller)
    {
        $this->routes["PATCH"][$uri] = $controller;
    }

    public function put($uri, $controller)
    {
        $this->routes["PUT"][$uri] = $controller;
    }

    public function delete($uri, $controller)
    {
        $this->routes["DELETE"][$uri] = $controller;
    }

}
