<?php

namespace App\Repositories;

use App\Core\Database;
use Exception;

abstract class Repository
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

    public function findOneById(string $table, int $id): ?array
    {
        return self::$database->findOneById($table, $id);
    }

    public function findAll(string $table, int $limit = 20): array
    {
        return self::$database->findAll($table, $limit);
    }

    public function findBy(string $table, array $params): array
    {
        return self::$database->findBy($table, $params)->fetchAll();
    }

    protected function execute(string $query, array $params): bool
    {
        return self::$database->execute($query, $params);
    }

    public function query(string $query, array $params = []): array
    {
        return self::$database->find($query, $params);
    }
}
