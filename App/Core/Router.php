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
        $controllerNamespaces = $this->app->getConfig()->get('controllers');

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

                foreach ($controllerNamespaces as $namespace) {
                    $fullControllerClass = $namespace . '\\' . $controllerClass;
                    if (class_exists($fullControllerClass) && method_exists($fullControllerClass, $controllerMethod)) {
                        return [
                            'class' => $fullControllerClass,
                            'method' => $controllerMethod,
                            'params' => $matches
                        ];
                    }
                }
            }
        }

        throw new Exception('Страница не существует');
    }
}
