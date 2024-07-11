<?php

namespace App\Core;

use App\Core\DB\Connection;
use Exception;
use ReflectionClass;
use ReflectionException;

class App
{
    private array $services = [];
    private array $repositories = [];
    private Config $config;
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

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
                if ($reflector->isAbstract()) {
                    continue;
                }

                $constructor = $reflector->getConstructor();
                $parameters = $constructor->getParameters();
                $dependencies = [];

                foreach ($parameters as $parameter) {
                    $type = $parameter->getType();
                    if ($type) {
                        $dependencyClassName = $type->getName();
                        if (isset($this->repositories[$dependencyClassName])) {
                            $dependencies[] = $this->repositories[$dependencyClassName];
                        } else {
                            $dependencies[] = new $dependencyClassName();
                        }
                    }
                }

                $serviceName = $reflector->getName();
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

            $validationRules = $controllerClass::getValidationRules($controllerMethod);

            if (!$this->validateRequest($request, $validationRules)) {
                return new Response(json_encode(['errors' => $this->validator->getErrors()]), 400);
            }

            $controller = $this->createController($controllerClass);

            $request->setParams(array_merge($request->getParams(), $params));

            return $controller->$controllerMethod($request);
        } catch (Exception $e) {
            return new Response('Ошибка сервера: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function createController(string $controllerClass): object
    {
        $dependencies = $this->getServiceForController($controllerClass);
        $reflector = new ReflectionClass($controllerClass);
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function getServiceForController(string $controllerClass): array
    {
        $reflector = new ReflectionClass($controllerClass);
        $constructor = $reflector->getConstructor();
        $dependencies = [];

        if ($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();
                if ($type && !$type->isBuiltin()) {
                    $dependencyClassName = $type->getName();
                    if (isset($this->services[$dependencyClassName])) {
                        $dependencies[] = $this->services[$dependencyClassName];
                    } else {
                        throw new Exception("Service $dependencyClassName not found for controller $controllerClass");
                    }
                }
            }
        }

        return $dependencies;
    }

    /**
     * @throws Exception
     */
    private function validateRequest(Request $request, array $rules): bool
    {
        return $this->validator->validate($request->getParams(), $rules);
    }
}
