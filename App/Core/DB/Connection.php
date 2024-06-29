<?php

namespace App\Core\DB;

use PDO;

class Connection
{
    private static ?Connection $instance = null;
    private PDO $conn;

    private function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        $this->conn = new PDO($dsn, $config['username'], $config['password']);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(array $config): Connection
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
