<?php

namespace App\Core\DB;

interface IRepository
{
    public function findOneById(int $id): ?array;
    public function findAll(int $limit = 20, int $offset = 0): array;
    public function execute(string $query, array $params): bool;
}
