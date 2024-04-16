<?php

namespace App\Core;

use RuntimeException;

class Router
{
    protected static array $routes = [];
    protected static array $route = [];

    public static function add($regexp, $callback, $method): void
    {
        self::$routes[$regexp] = [
            'callback' => $callback,
            'method' => $method
        ];
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function getRoute(): array
    {
        return self::$route;
    }

    protected static function removeQueryString($url): string
    {
        if ($url) {
            $params = explode('?', $url, 2);
            if (false === str_contains($params[0], '=')) {
                return rtrim($params[0], '/');
            }
        }
        return '';
    }

    public static function dispatch($url, $method): Response
    {
        $url = self::removeQueryString($url);
        $matchedRoute = null;

        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#^$pattern$#", $url, $matches) && $method === $route['method']) {
                $matchedRoute = $route;
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $matchedRoute['params'][$k] = $v;
                    }
                }
                break;
            }
        }

        if ($matchedRoute) {
            $callback = $matchedRoute['callback'];
            $response = $callback(new Request());
            return $response;
        } else {
            throw new RuntimeException('Страница не найдена', 404);
        }
    }

}
