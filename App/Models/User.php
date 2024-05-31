<?php

namespace App\Models;

use DateTime;

class User
{
    public ?int $id;
    public string $login;
    public string $password;
    public string $role;
    public ?DateTime $createdDate;

    public function __construct(?int $id, string $login, string $password, string $role, ?DateTime $createdDate)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->role = $role;
        $this->createdDate = $createdDate;
    }
}