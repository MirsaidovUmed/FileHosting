<?php

require_once __DIR__ . "/vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Core\App;
use App\Core\Request;
use Config\Config;

$config = new Config();
$config->load(__DIR__ . "/config.json");

$app = new App();

$request = new Request();
$request->setRequestParams();

$response = $app->handleRequest($request);
$response->sendResponse();
