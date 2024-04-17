<?php

namespace src\Core;

class App
{
    private static array $services = [];
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

    public function getService(string $serviceName): string
    {
        if (isset($this->services[$serviceName])) {
            return $this->services[$serviceName];
        }

        return null;
    }

    public function removeSrevice(string $serviceName): bool
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
}