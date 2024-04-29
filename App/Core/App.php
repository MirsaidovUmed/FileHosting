<?php

namespace App\Core;

class App
{
    private array $services = [];

    public function __construct(array $services = [])
    {
        $this->addServicesFromArray($services);
    }

    public function addService(string $serviceName, object $serviceInstance): void
    {
        $this->services[$serviceName] = $serviceInstance;
    }

    public function getService(string $serviceName): ?object
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

    public function addServicesFromArray(array $services): void
    {
        foreach ($services as $serviceName => $serviceInstance) {
            $this->addService($serviceName, $serviceInstance);
        }
    }
}
