<?php

namespace App\Core;

use Throwable;

class Router
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function processRequest(Request $request): Response
    {
        foreach (Web::URL_LIST as $url => $methodsList) {
            if ($url !== $request->getUrl()) {
                continue;
            }
            foreach ($methodsList as $httpMethod => $actionName) {
                if ($httpMethod !== $request->getMethod()) {
                    continue;
                }
                [$controllerClass, $controllerMethod] = explode('::', $actionName);
                $controllerClass = 'App\\Controllers\\' . $controllerClass;

                if (!class_exists($controllerClass) || !method_exists($controllerClass, $controllerMethod)) {
                    return Response::setError(404, 'Контроллер или метод не найден');
                }

                try {
                    $controller = new $controllerClass($this->app);
                    return $controller->$controllerMethod($request);
                } catch (Throwable $e) {
                    return Response::setError(500, 'Ошибка сервера: ' . $e->getMessage());
                }
            }
        }
        return Response::setError(404, 'Страница не существует');
    }
}
