<?php

namespace App\Models;

use App\Core\DB\Model;
use App\Core\Validator;
use DateTime;
use Exception;

class User extends Model
{
    private ?int $id;
    private string $login;
    private string $password;
    private string $role;
    private ?DateTime $createdDate;

    public static function getTableName(): string
    {
        return 'users';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }
}
