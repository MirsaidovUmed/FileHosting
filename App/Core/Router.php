<?php

namespace App\Core;

use App\Routes\Web;

class Router
{
    protected array $routes = [];
    protected App $app;

    public function __construct(App $app, array $routes = [])
    {
        $this->app = $app;
        $this->routes = $routes;
    }


    public function proccessRequest(Request $request): Response
    {
        try {
            $response = $this->dispatch($request);

            return $response;
        } catch (\Exception $e) {
            $this->errorPage404();
            return new Response(["Internal Server Error"], 500);
        }
    }

    public function errorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:' . $host . '404');
    }

    private function dispatch(Request $request): Response
    {
        $url = $request->getUrl();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if (isset($route['url']) && $route['url'] === $url && $route['method'] === $method) {
                $callback = $route['callback'];

                $response = $callback[$request];
                return $response;
            }
        }
        return new Response(['Not Found'], 404);
    }

    public function loadRoutes(): void
    {
        $userService = $this->app->getService('userService');
        $web = new Web($userService);
        $web->register($this, $userService);
    }

    public function get(string $uri, callable $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, callable $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function put(string $uri, callable $action)
    {
        $this->routes['PUT'][$uri] = $action;
    }

    public function delete(string $uri, callable $action)
    {
        $this->routes['DELETE'][$uri] = $action;
    }
}