<?php

namespace App\Core;

use App\Routes\Web;
use Throwable;

class Router
{
    const URL_LIST = [
        'user' => [
            'GET' => 'User::showUser',
            'POST' => 'User::createUser'
        ],
    ];

    public function processRequest(Request $request): Response
    {
        foreach (Web::URL_LIST as $url => $methodsList) {
            if ($url != $request->getUrl()) {
                continue;
            }
            foreach ($methodsList as $httpMethod => $actionName) {
                if ($httpMethod != $request->getMethod()) {
                    continue;
                }
                $classInfo = explode('::', $actionName);
                $controllerClass = 'App\\Controllers\\' . $classInfo[0] . 'Controller';
                $controllerMethod = $classInfo[1];
                try {
                    $controller = new $controllerClass();
                    return $controller->$controllerMethod($request);
                } catch (Throwable $e) {
                    return Response::setError(500, 'Ошибка сервера');
                }
            }
        }
        return Response::setError(404, 'Страница не существует');
    }
}