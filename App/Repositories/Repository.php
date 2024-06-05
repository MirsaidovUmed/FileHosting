<?php

namespace App\Repositories;

use App\Core\Database;
use Exception;
use PDO;
use PDOException;

abstract class Repository implements IRepository
{
    protected static ?Database $database = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (self::$database === null) {
            throw new Exception("Database not initialized");
        }
    }

    public static function setDatabase(Database $database): void
    {
        self::$database = $database;
    }

    protected function getConnection(): PDO
    {
        return self::$database->getConnection();
    }

    public function findOneById(string $table, int $id): ?array
    {
        $query = "SELECT * FROM $table WHERE id = :id";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findAll(string $table, int $limit = 20): array
    {
        $query = "SELECT * FROM $table LIMIT :limit";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll() ?: [];
    }

    public function findBy(string $table, array $params): array
    {
        $query = "SELECT * FROM $table WHERE " . implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($params)));
        $stmt = $this->getConnection()->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function execute(string $query, array $params): bool
    {
        try {
            $stmt = $this->getConnection()->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function query(string $query, array $params = []): array
    {
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll() ?: [];
    }
}
