<?php

namespace App\Models;

class Users
{
    private $id;
    private $login;
    private $password;
    private $createdDate;

    public function __construct($id, $login, $password, $createdDate)
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->createdDate = $createdDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function getCreatedAt()
    {
        return $this->createdDate;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'created_date' => $this->createdDate,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public static function fromArray($data)
    {
        return new self(
            $data['id'] ?? null,
            $data['login'] ?? null,
            $data['password'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson($json)
    {
        return self::fromArray(json_decode($json, true));
    }
}