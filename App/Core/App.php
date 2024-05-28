<?php

namespace App\Core;

use App\Services\Service;
use App\Core\Router;

class App
{
    public function __construct()
    {
    }

    public function handleRequest(Request $request): Response
    {
        return (new Router())->processRequest($request);
    }


    public function getService(string $serviceName): Service
    {
        $class = 'App\\Services\\' . $serviceName . 'Service';
        return new $class();
    }
}
