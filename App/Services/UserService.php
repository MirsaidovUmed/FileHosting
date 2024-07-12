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
    public function createUser(array $data): void
    {
        $user = new User();
        $user->setLogin($data['login']);
        $user->setPassword($data['password']);
        $user->setRole($data['role']);
        $this->userRepository->createUser($user);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $userId, array $data): void
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            $user->setLogin($data['login'] ?? $user->getLogin());
            $user->setPassword($data['password'] ?? $user->getPassword());
            $user->setRole($data['role'] ?? $user->getRole());
            $this->userRepository->updateUser($userId, $user);
        } else {
            throw new Exception("Пользователь не найден.");
        }
    }

    /**
     * @throws Exception
     */
    public function findById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function findAll(int $limit, int $offset): array
    {
        $return $this->userRepository->findAll($limit, $offset);
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $this->userRepository->deleteUser($userId);
        } else {
            throw new Exception("Пользователь не найден.");
        }
    }
}
