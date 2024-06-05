<?php

namespace App\Repositories;

use App\Models\User;
use DateTime;
use Exception;

class UserRepository extends Repository
{
    protected string $table = 'users';

    /**
     * @throws Exception
     */
    public function findById(int $id): ?User
    {
        $userData = $this->findOneById($this->table, $id);

        if ($userData) {
            $createdDate = isset($userData['created_date']) ? new DateTime($userData['created_date']) : null;
            return new User(
                $userData['id'],
                $userData['login'],
                $userData['password'],
                $userData['role'],
                $createdDate
            );
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function findAllUsers(int $limit = 20): array
    {
        $userData = $this->findAll($this->table, $limit);

        return array_map( function ($user) {
            $createdDate = isset($user['created_date']) ? new DateTime($user['created_date']) : null;
            return new User(
                $user['id'],
                $user['login'],
                $user['password'],
                $user['role'],
                $createdDate
            );
        }, $userData ?: []);
    }

    public function createUser(User $user): bool
    {
        $query = 'INSERT INTO users (login, password, role) VALUES (:login, :password, :role)';
        $params = [
            'login' => $user->login,
            'password' => $user->password,
            'role' => $user->role
        ];
        return $this->execute($query, $params);
    }

    public function updateUser(int $id, User $user): bool
    {
        $query = 'UPDATE users SET login = :login, password = :password, role = :role WHERE id = :id';
        $params = [
            'login' => $user->login,
            'password' => $user->password,
            'role' => $user->role,
            'id' => $id
        ];
        return $this->execute($query, $params);
    }

    public function deleteUser(int $id): bool
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $params = ['id' => $id];
        return $this->execute($query, $params);
    }
}
