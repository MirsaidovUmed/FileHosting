<?php

namespace App\Core;

use Exception;

class Router
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @throws Exception
     */
    public function getController(Request $request): array
    {
        foreach (Web::URL_LIST as $url => $methodsList) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+}/', '([a-zA-Z0-9_]+)', $url);
            if (!preg_match("#^$pattern$#", $request->getUrl(), $matches)) {
                continue;
            }

            array_shift($matches);

            foreach ($methodsList as $httpMethod => $actionName) {
                if ($httpMethod !== $request->getMethod()) {
                    continue;
                }

                [$controllerClass, $controllerMethod] = explode('::', $actionName);
                $controllerClass = 'App\\Controllers\\' . $controllerClass;

                if (!class_exists($controllerClass) || !method_exists($controllerClass, $controllerMethod)) {
                    throw new Exception('Контроллер или метод не найден');
                }

                return [
                    'class' => $controllerClass,
                    'method' => $controllerMethod,
                    'params' => $matches
                ];
            }
        }

        throw new Exception('Страница не существует');
    }
}
