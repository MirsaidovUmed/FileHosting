<?php

namespace App\Core\Interfaces;

interface UserInterface
{
    public function getId(): ?int;
    public function getLogin(): string;
    public function getPassword(): string;
    public function getRoleId(): int;
}
