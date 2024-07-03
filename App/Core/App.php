<?php

namespace App\Core;

use App\Core\AbstractClasses\Service;
use App\Core\DB\Connection;
use Exception;
use ReflectionClass;
use ReflectionException;

class App
{
    private array $services = [];
    private array $repositories = [];
    private Config $config;

    public function initConfig(Config $config): void
    {
        $this->config = $config;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function initRepositories(): void
    {
        $repositoryNamespaces = $this->config->get(Config::REPOSITORIES);
        foreach ($repositoryNamespaces as $repositoryNamespace) {
            foreach (get_declared_classes() as $class) {
                if (!str_starts_with($class, $repositoryNamespace)) {
                    continue;
                }

                $reflector = new ReflectionClass($class);

                if ($reflector->isAbstract()) {
                    continue;
                }

                $repositoryName = $reflector->getShortName();
                $this->repositories[$repositoryName] = $reflector->newInstance();
            }
        }

        $databaseConfig = $this->config->get(Config::DATABASE);
        $database = Connection::getInstance($databaseConfig);
        foreach ($this->repositories as $repository) {
            $repository->setDatabase($database);
        }
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function initServices(): void
    {
        $serviceNamespaces = $this->config->get(Config::SERVICES);
        foreach ($serviceNamespaces as $serviceNamespace) {
            foreach (get_declared_classes() as $class) {
                if (!str_starts_with($class, $serviceNamespace)) {
                    continue;
                }

                $reflector = new ReflectionClass($class);
                if (!$reflector->implementsInterface(IService::class) || $reflector->isAbstract()) {
                    continue;
                }

                $constructor = $reflector->getConstructor();
                $parameters = $constructor->getParameters();
                $dependencies = [];

                foreach ($parameters as $parameter) {
                    $type = $parameter->getType();
                    if ($type) {
                        $dependencyClassName = $type->getName();
                        $dependencies[] = new $dependencyClassName();
                    }
                }

                $serviceName = $reflector->getShortName();
                $this->services[$serviceName] = $reflector->newInstanceArgs($dependencies);
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
    public function getService(string $serviceName): Service
    {
        if (!isset($this->services[$serviceName])) {
            throw new Exception("Сервис не найден: " . $serviceName);
        }
        return $this->services[$serviceName];
    }
}
