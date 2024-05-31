<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;

class UserService extends Service
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(string $login, string $password, string $role): bool
    {
        $user = new User(null, $login, $password, $role, null);
        return $this->userRepository->createUser($user);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $userId, ?string $login = null, ?string $password = null, ?string $role = null): bool
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            if ($login !== null) {
                $user->login = $login;
            }

            if ($password !== null) {
                $user->password = $password;
            }

            if ($role !== null) {
                $user->role = $role;
            }
            return $this->userRepository->updateUser($user);
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
        $user = $this->userRepository->findById($userId);

        if ($user) {
            return $this->userRepository->deleteUser($user);
        }

        return false;
    }
}
