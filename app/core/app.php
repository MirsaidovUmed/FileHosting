<?php

namespace App\Core;

use App\Core\Router;

class App
{
    private array $services = [];

    public function __construct()
    {
        $this->registerService('router', function () {
            return new Router($this);
        });
    }

    public function registerService(string $name, callable $service)
    {
        $this->services[$name] = $service;
    }

    public function getService(string $name)
    {
        return $this->services[$name]();
    }

    public function getRouter(): Router
    {
        return $this->getService('router');
    }
}
