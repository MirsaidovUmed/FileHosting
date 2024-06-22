<?php

namespace App\Core;

use DateTime;
use Exception;
use PDOStatement;

abstract class Repository
{
    protected Connection $database;

    public function setDatabase(Connection $database): void
    {
        $this->database = $database;
    }

    abstract protected static function getModelClass(): string;

    /**
     * @throws Exception
     */
    protected function deserialize(array $data): object
    {
        $modelClass = static::getModelClass();

        if (isset($data['created_date'])) {
            $data['created_date'] = new DateTime($data['created_date']);
        }

        return new $modelClass(...$data);
    }

    public function findOneById(int $id): ?array
    {
        $modelClass = static::getModelClass();
        $modelInstance = new $modelClass();
        $table = $modelInstance::getTableName();
        $query = "SELECT * FROM $table WHERE id = :id";
        $params = ['id' => $id];
        return $this->query($query, $params)->fetch();
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $modelClass = static::getModelClass();
        $modelInstance = new $modelClass();
        $table = $modelInstance::getTableName();
        $query = "SELECT * FROM $table LIMIT :limit OFFSET :offset";
        $params = ['limit' => $limit, 'offset' => $offset];
        return $this->query($query, $params)->fetchAll();
    }

    public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->database->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function execute(string $query, array $params): bool
    {
        $stmt = $this->database->getConnection()->prepare($query);
        return $stmt->execute($params);
    }
}
