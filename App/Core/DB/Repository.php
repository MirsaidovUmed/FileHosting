<?php

namespace App\Core\DB;

use DateTime;
use Exception;
use PDOStatement;

abstract class Repository implements IRepository
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

    /**
     * @throws Exception
     */
    public function find(int $id): ?array
    {
        return $this->findOneById($id);
    }

    /**
     * @throws Exception
     */
    public function findOneById(int $id): ?array
    {
        $modelClass = static::getModelClass();
        if (!method_exists($modelClass, 'getTableName')) {
            throw new Exception("Метод getTableName не найден в классе $modelClass");
        }

        $table = $modelClass::getTableName();
        $query = "SELECT * FROM $table WHERE id = :id";
        $params = ['id' => $id];

        return $this->query($query, $params)->fetch();
    }

    /**
     * @throws Exception
     */
    public function findBy(array $criteria, array $sort = [], int $limit = 20, int $offset = 0): ?array
    {
        $modelClass = static::getModelClass();
        if (!method_exists($modelClass, 'getTableName')) {
            throw new Exception("Метод getTableName не найден в классе $modelClass");
        }

        $table = $modelClass::getTableName();
        $whereClause = $this->buildWhereClause($criteria);
        $orderClause = $this->buildOrderClause($sort);

        $query = "SELECT * FROM $table $whereClause $orderClause LIMIT :limit OFFSET :offset";
        $params = array_merge($criteria, ['limit' => $limit, 'offset' => $offset]);

        return $this->query($query, $params)->fetchAll();
    }

    /**
     * @throws Exception
     */
    public function findOneBy(array $criteria, array $sort = []): ?array
    {
        $modelClass = static::getModelClass();
        if (!method_exists($modelClass, 'getTableName')) {
            throw new Exception("Метод getTableName не найден в классе $modelClass");
        }

        $table = $modelClass::getTableName();
        $whereClause = $this->buildWhereClause($criteria);
        $orderClause = $this->buildOrderClause($sort);

        $query = "SELECT * FROM $table $whereClause $orderClause LIMIT 1";
        $params = $criteria;

        return $this->query($query, $params)->fetch();
    }

    /**
     * @throws Exception
     */
    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $modelClass = static::getModelClass();
        if (!method_exists($modelClass, 'getTableName')) {
            throw new Exception("Метод getTableName не найден в классе $modelClass");
        }

        $table = $modelClass::getTableName();
        $query = "SELECT * FROM $table LIMIT :limit OFFSET :offset";
        $params = ['limit' => $limit, 'offset' => $offset];

        return $this->query($query, $params)->fetchAll();
    }

    private function query(string $query, array $params = []): PDOStatement
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

    private function buildWhereClause(array $criteria): string
    {
        if (empty($criteria)) {
            return '';
        }

        $where = [];
        foreach ($criteria as $key => $value) {
            $where[] = "$key = :$key";
        }

        return 'WHERE ' . implode(' AND ', $where);
    }

    private function buildOrderClause(array $sort): string
    {
        if (empty($sort)) {
            return '';
        }

        $order = [];
        foreach ($sort as $key => $value) {
            $order[] = "$key $value";
        }

        return 'ORDER BY ' . implode(', ', $order);
    }

    /**
     * @throws Exception
     */
    public function save(object $model): void
    {
        $modelClass = static::getModelClass();
        if (get_class($model) !== $modelClass) {
            throw new Exception("Model is not an instance of $modelClass");
        }

        $table = $modelClass::getTableName();
        $fields = get_object_vars($model);
        $columns = array_keys($fields);
        $placeholders = array_map(fn($col) => ":$col", $columns);

        if ($model->getId() === null) {
            $query = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        } else {
            $setClause = implode(', ', array_map(fn($col) => "$col = :$col", $columns));
            $query = "UPDATE $table SET $setClause WHERE id = :id";
            $fields['id'] = $model->getId();
        }

        $this->execute($query, $fields);
    }

    /**
     * @throws Exception
     */
    public function delete(object $model): void
    {
        $modelClass = static::getModelClass();
        if (get_class($model) !== $modelClass) {
            throw new Exception("Model is not an instance of $modelClass");
        }

        $table = $modelClass::getTableName();
        $query = "DELETE FROM $table WHERE id = :id";
        $params = ['id' => $model->getId()];

        $this->execute($query, $params);
    }
}
