<?php

namespace App\Core\DB;

interface IRepository
{
    public function find(int $id): ?array;
    public function findBy(array $criteria, array $sort = [], int $limit = 20, int $offset = 0): ?array;
    public function findOneBy(array $criteria, array $sort = []): ?array;
    public function execute(string $query, array $params): bool;
}
