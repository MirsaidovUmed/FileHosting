<?php

namespace App\Repositories;

use App\Models\Users;
use App\Core\Database;
use PDO;

class UserRepository
{
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?Users
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute(['id' => $id]);
        $userData = $statement->fetch(PDO::FETCH_ASSOC);

        return $userData ? new Users(
            $userData['id'],
            $userData['login'],
            $userData['password'],
            $userData['created_date'],
        ) : null;
    }

    public function findByLogin(string $login): ?Users
    {
        $query = "SELECT * FROM users WHERE login = :login";
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute(['login' => $login]);
        $userData = $statement->fetch(PDO::FETCH_ASSOC);

        return $userData ? new Users(
            $userData['id'],
            $userData['login'],
            $userData['password'],
            $userData['created_date'],
        ) : null;
    }

    public function save(Users $user): bool
    {
        $query = "INSERT INTO users (login, password) VALUES (:login, :password)";
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute([
            'login' => $user->getLogin(),
            'password' => $user->getPassword()
        ]);

        return $success;
    }

    public function update(Users $user): bool
    {
        $query = "UPDATE users SET login = :login, password = :password WHERE id = :id";
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute([
            'login' => $user->getLogin(),
            'password' => $user->getPassword(),
            'id' => $user->getId()
        ]);

        return $success;
    }

    public function delete(Users $user): bool
    {
        $query = "DELETE FROM users WHERE id = :id";
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute(['id' => $user->getId()]);

        return $success;
    }
}
