<?php

namespace App\Core;

use App\Repositories\Repository;
use App\Services\IService;
use ReflectionClass;
use Exception;
use ReflectionException;

class App
{
    private array $services = [];
    private Config $config;

    /**
     * @throws ReflectionException
     */
    public function init(Config $config): void
    {
        $this->initConfig($config);
        $this->initRepositories();
        $this->initializeServices();
    }

    private function initConfig(Config $config): void
    {
        $this->config = $config;
    }

    private function initRepositories(): void
    {
        $database = Database::getInstance($this->config->get('database'));
        Repository::setDatabase($database);
    }

    /**
     * @throws ReflectionException
     */
    private function initializeServices(): void
    {
        $serviceNamespace = 'App\\Services';

        foreach (get_declared_classes() as $class) {
            if (str_starts_with($class, $serviceNamespace)) {
                $reflector = new ReflectionClass($class);
                if ($reflector->implementsInterface(IService::class) && !$reflector->isAbstract()) {
                    $serviceName = $reflector->getShortName();
                    $this->services[$serviceName] = $reflector->newInstance();
                }
            }
        }
    }

    public function handleRequest(Request $request): Response
    {
        try {
            $controllerInfo = (new Router($this))->getController($request);
            $controllerClass = $controllerInfo['class'];
            $controllerMethod = $controllerInfo['method'];
            $params = $controllerInfo['params'];

            $controller = new $controllerClass($this);
            $request->setParams(array_merge($request->getParams(), $params));

            return $controller->$controllerMethod($request);
        } catch (Exception $e) {
            return Response::setError(500, 'Ошибка сервера: ' . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function getService(string $serviceName): IService
    {
        if (!isset($this->services[$serviceName])) {
            throw new Exception("Сервис не найден: " . $serviceName);
        }

        return $this->services[$serviceName];
    }
}
