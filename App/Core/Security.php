<?php

namespace App\Core;

use App\Core\Interfaces\UserInterface;

class Security {
    private ?UserInterface $user;

    public function __construct(UserInterface $user = null) {
        $this->user = $user;
    }

    public function hasAccess(int $requiredRole): bool {
        return $this->user && $this->user->getRoleId() >= $requiredRole;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }
}
