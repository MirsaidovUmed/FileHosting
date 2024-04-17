<?php

require_once __DIR__ . "/vendor/autoload.php";

use App\Core\App;
use Config\Config;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;
use App\Core\Response;

$config = new Config();
$config->load(__DIR__ . "/config.json");

$configArray = $config->get('database');
var_dump($configArray);

$db = new Database($configArray);
$app = new App([]);

$router = new Router([]);

$request = new Request();

$response = $router->proccessRequest($request);

$response->send();