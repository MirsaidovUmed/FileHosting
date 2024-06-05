<?php

namespace App\Repositories;

use App\Core\Database;
use Exception;
use PDO;

interface IRepository
{
    public function findOneById(string $table, int $id): ?array;

    public function findAll(string $table, int $limit = 20): array;

    public function findBy(string $table, array $params): array;

    public function execute(string $query, array $params): bool;

    public function query(string $query, array $params = []): array;
}
