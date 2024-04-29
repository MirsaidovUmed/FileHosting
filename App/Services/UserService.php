<?php

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(string $login, string $password): bool
    {
        $user = new UserModel(null, $login, $password, null);

        return $this->userRepository->createUser($user);
    }

    public function updateUser(int $userId, string $login = null, string $password = null): bool
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            if ($login !== null) {
                $user->login = $login;
            }

            if ($password !== null) {
                $user->password = $password;
            }
        }

        return $this->userRepository->updateUser($user);
    }

    public function findById(int $userId): ?array
    {
        return $this->userRepository->findById($userId);
    }

    public function deleteUser(int $userId): bool
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            return $this->userRepository->deleteUser($user);
        }

        return false;
    }
}