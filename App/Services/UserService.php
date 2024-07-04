<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    public function createUser(array $data): bool
    {
        User::validate($data);
        $user = new User();
        $user->setLogin($data['login']);
        $user->setPassword($data['password']);
        $user->setRole($data['role']);
        return $this->userRepository->createUser($user);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $userId, array $data): bool
    {
        User::validate($data);
        $user = $this->userRepository->findById($userId);

        if ($user) {
            $user->setLogin($data['login'] ?? $user->getLogin());
            $user->setPassword($data['password'] ?? $user->getPassword());
            $user->setRole($data['role'] ?? $user->getRole());
            return $this->userRepository->updateUser($userId, $user);
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function findById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->deleteUser($userId);
    }
}
