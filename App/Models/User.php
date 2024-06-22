<?php

namespace App\Models;

use App\Core\Model;
use DateTime;

class User extends Model
{
    private ?int $id;
    private string $login;
    private string $password;
    private string $role;
    private ?DateTime $createdDate;

    public function __construct(?int $id, string $login, string $password, string $role, ?DateTime $createdDate)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
        $this->createdDate = $createdDate;
    }

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

    public function getCreatedDate(): ?DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(?DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}
