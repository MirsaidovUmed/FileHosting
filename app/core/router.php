<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addRoute(string $method, string $pattern, callable $callback): void
    {
        $this->routes[] = ['method' => $method, 'pattern' => $pattern, 'callback' => $callback];
    }

    public function processRequest(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($this->requestMatchesRoute($request, $route)) {
                return call_user_func($route['callback'], $request, $this->database); // Передаем Database в callback
            }
        }

        return $this->notFoundResponse();
    }

    private function requestMatchesRoute(Request $request, array $route): bool
    {
        return $request->getMethod() === $route['method'] && preg_match($route['pattern'], $request->getRoute());
    }

    private function notFoundResponse(): Response
    {
        $response = new Response();
        $response->setData('404 Not Found');
        $response->setHeaders(['HTTP/1.1 404 Not Found']);
        return $response;
    }
}
