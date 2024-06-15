<?php

namespace App\Repositories;

use App\Core\Database;

interface IRepository
{
    public function setDatabase(Database $database): void;
    public function findOneById(string $table, int $id): ?array;
    public function findAll(string $table, int $limit = 20): array;
    public function findBy(string $table, array $params): array;
    public function execute(string $query, array $params): bool;
    public function query(string $query, array $params = []): array;
}
