<?php

namespace App\Repositories;

use App\Models\User;
use DateTime;
use Exception;
use App\Core\Repository;

class UserRepository extends Repository
{
    protected static function getModelClass(): string
    {
        return User::class;
    }

    /**
     * @throws Exception
     */
    public function findById(int $id): ?User
    {
        $userData = $this->findOneById($id);

        if ($userData) {
            $userData['created_date'] = isset($userData['created_date']) ? new DateTime($userData['created_date']) : null;
            return $this->deserialize($userData);
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function findAllUsers(int $limit = 20, int $offset = 0): array
    {
        $userDataArray = $this->findAll($limit, $offset);

        return array_map(function ($userData) {
            $userData['created_date'] = isset($userData['created_date']) ? new DateTime($userData['created_date']) : null;
            return $this->deserialize($userData);
        }, $userDataArray ?: []);
    }

    public function createUser(User $user): bool
    {
        $query = 'INSERT INTO users (login, password, role, created_date) VALUES (:login, :password, :role, :created_date)';
        $params = [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'created_date' => $user->getCreatedDate()?->format('Y-m-d H:i:s')
        ];
        return $this->execute($query, $params);
    }

    public function updateUser(int $id, User $user): bool
    {
        $query = 'UPDATE users SET login = :login, password = :password, role = :role, created_date = :created_date WHERE id = :id';
        $params = [
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'created_date' => $user->getCreatedDate()?->format('Y-m-d H:i:s'),
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
