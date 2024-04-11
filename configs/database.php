<?php

namespace Configs;

use PDO;

return [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'FileHosting',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ],
];