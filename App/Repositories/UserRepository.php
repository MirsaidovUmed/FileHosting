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
    public function createUser(User $user): void
    {
        $query = 'INSERT INTO users (login, password, role) VALUES (:login, :password, :role)';
        $params = [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole()
        ];
        if (!$this->execute($query, $params)) {
            throw new Exception("Не удалось создать пользователя.");
        }
    }


    /**
     * @throws Exception
     */
    public function updateUser(int $id, User $user): void
    {
        $query = 'UPDATE users SET login = :login, password = :password, role = :role WHERE id = :id';
        $params = [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'id' => $id
        ];
        if (!$this->execute($query, $params)) {
            throw new Exception("Не удалось обновить пользователя.");
        }
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
    public function deleteUser(int $id): void
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $params = ['id' => $id];
        if (!$this->execute($query, $params)) {
            throw new Exception("Не удалось удалить пользователя.");
        }
    }
}
