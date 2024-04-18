<?php

namespace App\Core;

class App
{
    private array $services = [];
    
    public function __construct(array $services)
    {
        foreach ($services as $serviceName => $serviceInstance) {
            $this->setService($serviceName, $serviceInstance);
        }
    }

    public function setService(string $serviceName, string $serviceInstance): void
    {
        $this->services[$serviceName] = $serviceInstance;
    }

    public function getService(string $serviceName): ?string
    {
        if (isset($this->services[$serviceName])) {
            return $this->services[$serviceName];
        }

        return null;
    }

    public function removeService(string $serviceName): bool
    {
        if (isset($this->services[$serviceName])) {
            unset($this->services[$serviceName]);
            return true;
        }

        return false;
    }

    public function cleanService(string $serviceName): array
    {
        return $this->services = [];
    }

    public function getDatabase(): ?Database
    {
        if (!isset($this->services['database'])) {
            $config = $this->services['config'];
            $this->services['database'] = new Database($config->get('database'));
        }

        return $this->services['database'];
    }
}
