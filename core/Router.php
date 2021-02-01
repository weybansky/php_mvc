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

    public $matchUri;
    public $matchController;
    public $matchParams = [];

    public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    public function direct($uri, $requestMethod)
    {
        $routes = $this->routes[$requestMethod];

        if ($this->match($uri, $routes)) {
            return $this->callAction(
                ...explode("@", $this->matchController),
            );

        } elseif ($this->match($uri, $this->routes["GET"])) {
            throw new Exception("Method not allowed", 405);
        }

        throw new Exception("No Route Define for this URI", 404);
    }

    function match($uri, $routes) {
        // get no of sections in uri
        $uriSections = explode("/", $uri);

        // aviod loop if no param
        if (array_key_exists($uri, $routes)) {
            $this->matchUri = $uri;
            $this->matchController = $routes[$uri];
            $this->setMatchParams($this->matchUri, $uri);
            return true;
        }

        // get only routes with same sections
        $routes = array_filter($routes, function ($key) use ($uriSections) {
            $routeUriSections = explode("/", $key);
            return (count($routeUriSections) == count($uriSections));
        }, ARRAY_FILTER_USE_KEY);

        foreach ($routes as $key => $value) {
            $routeUriSections = explode("/", $key);
            $status = [];

            for ($index = 0; $index < count($routeUriSections); $index++) {
                if ($this->isRouteSectionParam($routeUriSections[$index])) {
                    // if param => true
                    array_push($status, 1);
                } elseif (!$this->isRouteSectionParam($routeUriSections[$index]) && $uriSections[$index] == $routeUriSections[$index]) {
                    // if not param and equal => true
                    array_push($status, 1);
                } else {
                    array_push($status, 0);
                }
            }

            if (array_search(0, $status) === false) {
                $this->matchUri = $key;
                $this->matchController = $value;
                $this->setMatchParams($this->matchUri, $uri);
                break;
            } else {continue;}
        }

        return isset($this->matchUri);
    }

    protected function isRouteSectionParam($routeSection)
    {
        return preg_match("/\{[a-zA-Z]{0,}\}/", $routeSection) ? true : false;
    }

    protected function setMatchParams($matchUri, $currentUri)
    {
        $matchUriSections = explode("/", $matchUri);
        $currentUriSections = explode("/", $currentUri);
        for ($index = 0; $index < count($currentUriSections); $index++) {
            if ($this->isRouteSectionParam($matchUriSections[$index])) {
                $key = trim($matchUriSections[$index], "{}");
                $this->matchParams[$key] = $currentUriSections[$index];
            }
        }
    }

    protected function callAction($controller, $method)
    {
        $controller = "App\\Controllers\\{$controller}";
        $instance = new $controller;

        if (!method_exists($instance, $method)) {
            throw new Exception("{$controller} does not respond to the {$method} method", 1);
        }

        return $instance->$method(...array_values($this->matchParams));
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
