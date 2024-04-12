<?php

namespace App\Services;

use App\Models\Users;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserById(int $id): ?Users
    {
        return $this->userRepository->findById($id);
    }

    public function getUserByLogin(string $email): ?Users
    {
        return $this->userRepository->findByLogin($email);
    }

    public function createUser(string $login, string $password): bool
    {
        $user = new Users(null, $login, $password, null);
        return $this->userRepository->save($user);
    }

    public function updateUser(Users $user): bool
    {
        return $this->userRepository->update($user);
    }

    public function deleteUser(Users $user): bool
    {
        return $this->userRepository->delete($user);
    }
}