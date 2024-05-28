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

$config = new Config();
$config->load(__DIR__ . "/config.json");
$configArray = $config->get('database');

$db = Database::getInstance($configArray);

$app = new App();

$router = new Router();

$request = new Request();
