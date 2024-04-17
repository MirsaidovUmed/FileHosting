<?php

require_once __DIR__ . "/vendor/autoload.php";

use src\Core\App;
use Config\Config;
use src\Core\Database;
use src\Core\Request;
use src\Core\Router;
use src\Core\Response;

$config = new Config();
$config->load(__DIR__ . "/composer.json");

$configArray = $config->get('database');

$db = new Database($configArray);
$app = new App([]);

$router = new Router([]);

$request = new Request();

$response = $router->proccessRequest($request);

$response->send();
