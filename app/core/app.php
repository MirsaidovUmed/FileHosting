<?php

namespace App\Core;

use Exception;

class App
{
    private array $services = [];

    public function __construct(Database $database)
    {
        $this->registerService('database', $database);
    }

    public function registerService(string $serviceName, $serviceInstance): void
    {
        $this->services[$serviceName] = $serviceInstance;
    }

    /**
     * @throws Exception
     */
    public function getService(string $service): ?string
    {
        if ($this->hasService($service)) {
            return $this->services[$service];
        }

        throw new Exception("Сервис '{$service}' не зарегистрирован.");
    }

    public function hasService(string $service): bool
    {
        return isset($this->services[$service]);
    }
}
