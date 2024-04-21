<?php

namespace App\Models;

use DateTime;

class UserModel
{
    public int $id;
    public string $login;
    public string $password;
    public DateTime $createdDate;

    public function __construct(?int $id, string $login, string $password, ?DateTime $createdDate)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->createdDate = $createdDate;
    }
}