<?php

use App\Models\UserModel;
use App\Core\Database;

class UserRepository
{
    private static ?UserRepository $instance = null;
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public static function getInstance(Database $db): UserRepository
    {
        if (self::$instance === null) {
            self::$instance = new self($db);
        }
        return self::$instance;
    }

    public function findById(int $id): ?UserModel
    {
        $userData = $this->db->findOneById('users', $id);

        if ($userData) {
            return new UserModel(
                $userData['id'],
                $userData['login'],
                $userData['password'],
                new DateTime($userData['created_date'])
            );
        } else {
            return null;
        }
    }

    public function findAll(int $limit = 20): array
    {
        $query = 'SELECT * FROM users LIMIT :limit';

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->db->getConnection()->commit();

        return $userData ?: [];
    }


    public function createUser(UserModel $user): bool
    {
        $query = 'INSERT INTO users (login, password) VALUES (:login, :password)';
        $stmt = $this->db->getConnection()->prepare($query);
        $success = $stmt->execute([
            'login' => $user->login,
            'password' => $user->password
        ]);

        if ($success) {
            $this->db->getConnection()->commit();
        } else {
            $this->db->getConnection()->rollBack();
        }

        return $success;
    }

    public function update(UserModel $user): bool
    {
        $query = 'UPDATE users SET login = :login, password = :password WHERE id = :id';
        $stmt = $this->db->getConnection()->prepare($query);
        $success = $stmt->execute([
            'login' => $user->login,
            'password' => $user->password
        ]);
        if ($success) {
            $this->db->getConnection()->commit();
        } else {
            $this->db->getConnection()->rollBack();
        }

        return $success;
    }

    public function deleteUser(UserModel $user): bool
    {
        $query = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->db->getConnection()->prepare($query);
        $success = $stmt->execute(['id' => $user->id]);

        if ($success) {
            $this->db->getConnection()->commit();
        } else {
            $this->db->getConnection()->rollBack();
        }

        return $success;
    }
}