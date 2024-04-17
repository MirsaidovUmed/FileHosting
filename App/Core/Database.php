<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;
    private PDO $conn;

    public function __construct(array $config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']}";
        $this->conn = new PDO($dsn, $config['username'], $config['password']);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(array $config): Database
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

    public function find(string $query, array $params = []): array
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function findOneById(string $table, int $id): ?array
    {
        $query = "SELECT * FROM $table WHERE id = :id";

        $statement = $this->conn->prepare($query);
        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function findAll(string $table, array $params = [], int $limit = 20): array
    {
        $query = "SELECT * FROM $table LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: [];
    }

    public function findBy(string $table, array $params = []): PDOStatement
    {
        $query = "SELECT FROM $table WHERE";
        $paramsString = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($params)));
        $query .= $paramsString;

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt;
    }
}
