<?php

namespace App\Services;

use App\Models\User;

interface IUserService extends IService
{
    public function createUser(string $login, string $password, string $role): bool;
    public function updateUser(int $userId, ?string $login = null, ?string $password = null, ?string $role = null): bool;
    public function findById(int $userId): ?User;
    public function deleteUser(int $userId): bool;
}
