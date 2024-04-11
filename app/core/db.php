<?php

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
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

    public function findOneBy(string $table, int $id): ?array
    {
        $query = "SELECT * FROM $table WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    public function findAll(string $table): PDOStatement
    {
        $query = "SELECT * FROM $table";

        $statement = $this->connection->query($query);

        return $statement;
    }

    public function find(string $table, int $id): ?array
    {
        $query = "SELECT * FROM $table WHERE id = :id";

        $statement = $this->connection->prepare($query);
        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }
}