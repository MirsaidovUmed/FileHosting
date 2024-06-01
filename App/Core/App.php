<?php

namespace App\Core;

use App\Repositories\Repository;
use App\Services;
use Exception;

class App
{
    private array $services = [];

    public function __construct(Config $config)
    {
        $database = Database::getInstance($config->get('database'));
        Repository::setDatabase($database);
        $this->initializeServices();
    }

    private function initializeServices(): void
    {
        $this->services = [
            'UserService' => new Services\UserService(),
        ];
    }

    public function handleRequest(Request $request): Response
    {
        return (new Router($this))->processRequest($request);
    }

    /**
     * @throws Exception
     */
    public function getService(string $serviceName): Services\Service
    {
        if (!isset($this->services[$serviceName])) {
            throw new Exception("Сервис не найден: " . $serviceName);
        }

        return $this->services[$serviceName];
    }
}
