<?php

namespace App\Core;

use App\Core\DB\Connection;
use App\Core\Interfaces\UserInterface;
use App\Services\AuthService;
use App\Models\User;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class App
{
    private array $services = [];
    private array $repositories = [];
    private Config $config;
    private Validator $validator;
    private AuthService $authService;

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

         if (isset($this->services[AuthService::class])) {
            $this->authService = $this->services[AuthService::class];
        } else {
            throw new Exception('AuthService not found');
        }
    }

    public function handleRequest(Request $request): Response
    {
        try {
            $controllerInfo = (new Router($this))->getController($request);
            $controllerClass = $controllerInfo['class'];
            $controllerMethod = $controllerInfo['method'];
            $params = $controllerInfo['params'];

            $user = $this->getUserFromRequest($request);
            if (!$user) {
                return new Response('Пользователь не аутентифицирован', 401);
            }

            $requiredRoleId = $controllerClass::getRequiredRole($controllerMethod);
            if (!$this->authService->authorize($user, $requiredRoleId)) {
                return new Response('Доступ запрещен', 403);
            }

            $validationRules = $controllerClass::getValidationRules($controllerMethod);
            if (!$this->validateRequest($request, $validationRules)) {
                return new Response(json_encode(['errors' => $this->validator->getErrors()]), 400);
            }

            $controller = $this->createController($controllerClass);
            $request->setParams(array_merge($request->getParams(), $params));

            $method = new ReflectionMethod($controller, $controllerMethod);
            $dependencies = $this->getMethodDependencies($method);

            return $method->invokeArgs($controller, array_merge([$request], $dependencies));
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
     * @throws ReflectionException
     * @throws Exception
     */
    private function getMethodDependencies(ReflectionMethod $method): array
    {
        $dependencies = [];
        foreach ($method->getParameters() as $parameter) {
            $type = $parameter->getType();
            if ($type && !$type->isBuiltin()) {
                $dependencyClassName = $type->getName();
                if (isset($this->services[$dependencyClassName])) {
                    $dependencies[] = $this->services[$dependencyClassName];
                } else {
                    throw new Exception("Service $dependencyClassName not found for method {$method->getName()}");
                }
            } else {
                $dependencies[] = null;
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

    private function getUserFromRequest(Request $request): ?UserInterface
    {
        $token = $request->getHeader('Authorization');
        if ($token) {
            return $this->authService->getUserByToken($token);
        }
        return null;
    }
}
