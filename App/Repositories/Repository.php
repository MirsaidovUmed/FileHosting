<?php

namespace App\Repositories;

use App\Core\Connection;
use DateTime;
use Exception;

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
        return $this->database->query($query, $params)->fetch();
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $modelClass = static::getModelClass();
        $modelInstance = new $modelClass();
        $table = $modelInstance::getTableName();
        $query = "SELECT * FROM $table LIMIT :limit OFFSET :offset";
        $params = ['limit' => $limit, 'offset' => $offset];
        return $this->database->query($query, $params)->fetchAll();
    }

    public function execute(string $query, array $params): bool
    {
        return $this->database->execute($query, $params);
    }
}
