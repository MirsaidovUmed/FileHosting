<?php

namespace App\Repositories;

use App\Models\UserModel;
use App\Core\Database;
use DateTime;
use PDO;

class UserRepository
{
    private static ?UserRepository $instance = null;
    private Database $database;

    private function __construct(Database $database)
    {
        $this->database = $database;
    }

    public static function getInstance(Database $database): UserRepository
    {
        if (self::$instance === null) {
            self::$instance = new self($database);
        }
        return self::$instance;
    }

    public function findById(int $id): ?UserModel
    {
        $userData = $this->database->findOneById('users', $id);

        if ($userData) {
            $createdDate = isset($userData['created_date']) ? new DateTime($userData['created_date']) : null;
            return new UserModel(
                $userData['id'],
                $userData['login'],
                $userData['password'],
                $createdDate
            );
        } else {
            return null;
        }
    }

    public function findAll(int $limit = 20): array
    {
        $query = 'SELECT * FROM users LIMIT :limit';

        $stmt = $this->database->getConnection()->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->database->getConnection()->commit();

        return $userData ?: [];
    }


    public function createUser(UserModel $user): bool
    {
        $query = 'INSERT INTO users (login, password) VALUES (:login, :password)';
        $stmt = $this->database->getConnection()->prepare($query);
        $success = $stmt->execute([
            'login' => $user->login,
            'password' => $user->password
        ]);

        if ($success) {
            $this->database->getConnection()->commit();
        } else {
            $this->database->getConnection()->rollBack();
        }

        return $success;
    }

    public function update(UserModel $user): bool
    {
        $query = 'UPDATE users SET login = :login, password = :password WHERE id = :id';
        $stmt = $this->database->getConnection()->prepare($query);
        $success = $stmt->execute([
            'login' => $user->login,
            'password' => $user->password,
            'id' => $user->id
        ]);
        if ($success) {
            $this->database->getConnection()->commit();
        } else {
            $this->database->getConnection()->rollBack();
        }

        return $success;
    }

    public function deleteUser(UserModel $user): bool
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->database->getConnection()->prepare($query);
        $success = $stmt->execute(['id' => $user->id]);

        if ($success) {
            $this->database->getConnection()->commit();
        } else {
            $this->database->getConnection()->rollBack();
        }

        return $success;
    }
}