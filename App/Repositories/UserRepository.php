<?php

namespace App\Repositories;

use App\Models\User;
use App\Core\Database;
use DateTime;
use PDO;
use Exception;

class UserRepository extends Repository
{
    /**
     * @throws Exception
     */
    public function findById(int $id): ?User
    {
        $userData = self::$database->findOneById('users', $id);

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

    public function findAll(int $limit = 20): array
    {
        $query = 'SELECT * FROM users LIMIT :limit';

        $stmt = self::$database->getConnection()->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(/**
         * @throws Exception
         */ function ($user) {
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
        $stmt = self::$database->getConnection()->prepare($query);
        return $stmt->execute([
            'login' => $user->login,
            'password' => $user->password,
            'role' => $user->role
        ]);
    }

    public function updateUser(User $user): bool
    {
        $query = 'UPDATE users SET login = :login, password = :password, role = :role WHERE id = :id';
        $stmt = self::$database->getConnection()->prepare($query);
        return $stmt->execute([
            'login' => $user->login,
            'password' => $user->password,
            'role' => $user->role,
            'id' => $user->id
        ]);
    }

    public function deleteUser(User $user): bool
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = self::$database->getConnection()->prepare($query);
        return $stmt->execute(['id' => $user->id]);
    }
}
