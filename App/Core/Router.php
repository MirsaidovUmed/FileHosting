<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function proccessRequest(Request $request): Response
    {
        try {
            $response = $this->dispatch($request);

            return $response;
        } catch (\Exception $e) {
            return new Response(["Internal Server Error"], 500);
        }
    }

    public function dispatch(Request $request): Response
    {
        $url = $request->getUrl();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if ($route['url'] === $url && $route['method'] === $method) {
                $callback = $route['callback'];

                $response = $callback[$request];
                return $response;
            }
        }
        return new Response(['Not Found'], 404);
    }
}