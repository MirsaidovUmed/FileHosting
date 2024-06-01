<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;

class UserService extends Service
{
    protected function initializeRepositories(): void
    {
        $this->repositories['User'] = new UserRepository();
    }

    /**
     * @throws Exception
     */
    public function createUser(string $login, string $password, string $role): bool
    {
        $user = new User(null, $login, $password, $role, null);
        return $this->getRepository('User')->createUser($user);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $userId, ?string $login = null, ?string $password = null, ?string $role = null): bool
    {
        $user = $this->getRepository('User')->findById($userId);

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
            return $this->getRepository('User')->updateUser($userId, $user);
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function findById(int $userId): ?User
    {
        return $this->getRepository('User')->findById($userId);
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): bool
    {
        return $this->getRepository('User')->deleteUser($userId);
    }
}
