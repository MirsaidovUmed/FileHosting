<?php

require_once __DIR__ . "/../autoload.php";

use App\Core\App;
use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;

$config = require_once __DIR__ . '/../configs/database.php';
$db = new Database($config);

$app = new App($db);

$router = new Router($db);

$request = new Request();
$response = $router->processRequest($request);

$response->send();
