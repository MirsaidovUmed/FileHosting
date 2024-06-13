<?php

namespace App\Core;

use App\Repositories\IRepository;
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
     * @throws Exception
     */
    private function initializeServices(): void
    {
        $serviceNamespace = 'App\\Services';

        foreach (get_declared_classes() as $class) {
            if (str_starts_with($class, $serviceNamespace)) {
                $reflector = new ReflectionClass($class);
                if ($reflector->implementsInterface(IService::class) && !$reflector->isAbstract()) {
                    $constructor = $reflector->getConstructor();
                    $parameters = $constructor->getParameters();
                    $dependencies = [];

                    foreach ($parameters as $parameter) {
                        $type = $parameter->getType();
                        if ($type && !$type->isBuiltin()) {
                            $dependencyClassName = $type->getName();
                            if (is_subclass_of($dependencyClassName, IRepository::class)) {
                                $dependencies[] = $this->getRepository($dependencyClassName);
                            }
                        }
                    }
                    $serviceName = $reflector->getShortName();
                    $this->services[$serviceName] = $reflector->newInstanceArgs($dependencies);
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function getRepository(string $repositoryClass): IRepository
    {
        if (!class_exists($repositoryClass)) {
            throw new Exception("Репозиторий не найден: " . $repositoryClass);
        }
        return new $repositoryClass();
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
