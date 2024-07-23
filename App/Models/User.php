<?php

namespace App\Models;

use App\Core\DB\Model;
use App\Core\Interfaces\UserInterface;
use DateTime;

class User extends Model implements UserInterface
{

    const ROLE_ADMIN = 1;
    const ROLE_USER = 2;

    private ?int $id;
    private string $login;
    private string $password;
    private int $roleId;

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

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }
}
