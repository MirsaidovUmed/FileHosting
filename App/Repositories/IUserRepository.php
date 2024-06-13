<?php

namespace App\Repositories;

use App\Models\User;

interface IUserRepository extends IRepository
{
    public function findById(int $id): ?User;
    public function findAllUsers(int $limit = 20): array;
    public function createUser(User $user): bool;
    public function updateUser(int $id, User $user): bool;
    public function deleteUser(int $id): bool;
}
