<?php

require_once __DIR__ . "/../autoload.php";

use App\Core\App;
use App\Core\Request;
use App\Core\Response;

$app = new App();

$router = $app->getRouter();

$request = new Request();
$url = $request->getRoute();
$method = $request->getMethod();
$response = $router->dispatch($url, $method);

$response->send();
