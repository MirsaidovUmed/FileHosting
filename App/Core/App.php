<?php

namespace App\Core;

use App\Services\Service;
use Exception;

class App
{
    public function __construct()
    {
    }

    public function handleRequest(Request $request): Response
    {
        return (new Router($this))->processRequest($request);
    }

    /**
     * @throws Exception
     */
    public function getService(string $serviceName): Service
    {
        $class = 'App\\Services\\' . $serviceName . 'Service';
        if (!class_exists($class)) {
            throw new Exception("Сервис не найден: " . $serviceName);
        }
        return new $class();
    }
}
