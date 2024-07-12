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

    public function createUser(array $data): void
    {
        $user = new User();
        $user->setLogin($data['login']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
        $user->setRole($data['role']);
        $this->userRepository->createUser($user);
    }

    public function updateUser(int $userId, array $data): void
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            $user->setLogin($data['login'] ?? $user->getLogin());
            if (isset($data['password'])) {
                $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
            }
            $user->setRole($data['role'] ?? $user->getRole());
            $this->userRepository->updateUser($userId, $user);
        } else {
            throw new Exception("Пользователь не найден.");
        }
    }

    public function findById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    public function findAll(int $limit, int $offset): array
    {
        return $this->userRepository->findAll($limit, $offset);
    }

    public function deleteUser(int $userId): void
    {
        $user = $this->userRepository->findById($userId);
        if ($user) {
            $this->userRepository->deleteUser($userId);
        } else {
            throw new Exception("Пользователь не найден.");
        }
    }

    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return null;
    }
}
