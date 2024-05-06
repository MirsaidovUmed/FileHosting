<?php

require_once __DIR__ . "/vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use App\Core\App;
use Config\Config;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Core\Response;
use App\Services\UserService;
use App\Repositories\UserRepository;


$config = new Config();
$config->load(__DIR__ . "/config.json");
$configArray = $config->get('database');

$db = Database::getInstance($configArray);

$userRepository = UserRepository::getInstance($db);
$app = new App([
    'db' => $db,
    'userService' => new UserService($userRepository),
]);

$userService = $app->getService('userService');

$router = new Router($app);
$router->loadRoutes();

$request = new Request();
$response = $router->proccessRequest($request);

$response->send();
