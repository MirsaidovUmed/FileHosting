<?php

require_once __DIR__ . '/../../autoload.php';
$config = require_once __DIR__ . '/../configs/database.php';

use App\Core\Router;
use App\Core\Request;
use App\Controllers\UserController;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Core\Database;
use App\Core\App;

$database = new Database($config);
$userRepository = new UserRepository($database);
$userService = new UserService($userRepository);

$router = new Router();

$router->add('/users/{id}', function (Request $request) use ($userService) {
    $controller = new UserController($userService);
    return $controller->getUserById($request);
}, 'GET');

$router->add('/users', function (Request $request) use ($userService) {
    $controller = new UserController($userService);
    return $controller->createUser($request);
}, 'POST');

$router->add('/users/{id}', function (Request $request) use ($userService) {
    $controller = new UserController($userService);
    return $controller->updateUser($request);
}, 'PUT');

$router->add('/users/{id}', function (Request $request) use ($userService) {
    $controller = new UserController($userService);
    return $controller->deleteUser($request);
}, 'DELETE');

$request = new Request();
$response = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$response->send();
