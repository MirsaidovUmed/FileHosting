<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    public function __construct(array $config)
    {
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];
        $database = $config['database'];
        $charset = $config['charset'];
        $options = $config['options'];

        try {
            $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
            $this->connection = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new PDOException("Connection failed: " . $e->getMessage());
        }
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
        return $this->connection;
    }

    public function findBy(string $table, array $conditions): PDOStatement
    {
        $query = "SELECT * FROM $table WHERE ";
        $conditionsString = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($conditions)));
        $query .= $conditionsString;

        $statement = $this->connection->prepare($query);
        foreach ($conditions as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        $statement->execute();

        return $statement;
    }

    public function findOneById(string $table, int $id): ?array
    {
        $query = "SELECT * FROM $table WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
    
    public function findAll(string $table, int $limit = 20): PDOStatement
    {
        $query = "SELECT * FROM $table LIMIT :limit";
        $statement = $this->connection->prepare($query);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement;
    }
}