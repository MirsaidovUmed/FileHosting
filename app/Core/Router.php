<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, string $callback): void
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, string $callback): void
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function processRequest($request): string
    {
        $method = $request->getMethod();
        $path = $request->getRoute();
        $params = $request->getData();

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            return call_user_func($callback, $params);
        } else {
            return "404 Not Found";
        }
    }
}