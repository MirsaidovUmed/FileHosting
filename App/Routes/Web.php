<?php

namespace App\Routes;

use App\Core\Router;
use App\Controllers\UserController;
use App\Services\UserService;

class Web
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Router $router, UserService $userService): void
    {
        $web = new self($userService);
        
        $userController = new UserController($web->userService);

        // $router->get('/users', [$userController, 'getAllUsers']);
        $router->get('/users/{id}',[$userController, 'getUserById']);
        $router->post('/users', [$userController, 'createUser']);
        $router->put('/users/{id}', [$userController, 'updateUser']);
        $router->delete('/users/{id}', [$userController, 'deleteUser']);
    }
}