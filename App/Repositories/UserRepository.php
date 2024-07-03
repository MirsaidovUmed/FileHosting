<?php

namespace App\Repositories;

use App\Core\DB\Repository;
use App\Models\User;
use DateTime;
use Exception;

class UserRepository extends Repository
{
    protected static function getModelClass(): string
    {
        return User::class;
    }

    /**
     * @throws Exception
     */
    public function createUser(User $user): bool
    {
        $query = 'INSERT INTO users (login, password, role) VALUES (:login, :password, :role)';
        $params = [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ];
        return $this->execute($query, $params);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $id, User $user): bool
    {
        $query = 'UPDATE users SET login = :login, password = :password, role = :role WHERE id = :id';
        $params = [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'id' => $id
        ];
        return $this->execute($query, $params);
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): ?User
    {
        $data = $this->findOneById($id);
        return $data ? $this->deserialize($data) : null;
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $id): bool
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $params = ['id' => $id];
        return $this->execute($query, $params);
    }
}
