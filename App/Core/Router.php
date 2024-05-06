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
            return $this->dispatch($request);
        } catch (\Exception $e) {
            $this->errorPage404();
            return new Response(["Internal Server Error"], 500);
        }
    }

    public function errorPage404(): void
    {
        header('HTTP/1.1 404 Not Found');
        header('Location: /');
        exit();
    }

//    private function dispatch(Request $request): Response
//    {
//        $url = $request->getUrl();
//        $method = $request->getMethod();
//
//        foreach ($this->routes as $route) {
//            if (isset($route['url']) && $route['url'] === $url && $route['method'] === $method) {
//                $callback = $route['callback'];
//
//                return $callback[$request];
//            }
//        }
//        return new Response(['Not Found'], 404);
//    }


    private function dispatch(Request $request): Response
    {
        $url = $request->getUrl();
        $method = $request->getMethod();

        foreach ($this->routes as $route) {
            if (isset($route['url']) && $route['url'] === $url && $route['method'] === $method) {
                $callback = $route['callback'];

                if (is_callable($callback)) {
                    return $callback($request);
                } elseif (is_array($callback) && count($callback) == 2) {
                    list($class, $method) = $callback;
                    $object = new $class;
                    return $object->$method($request);
                }
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

    public function get(string $uri, callable $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, callable $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function put(string $uri, callable $action): void
    {
        $this->routes['PUT'][$uri] = $action;
    }

    public function delete(string $uri, callable $action): void
    {
        $this->routes['DELETE'][$uri] = $action;
    }
}