<?php

namespace App\Core;

use App\Models\User;

class Security {
    private User $user;

    public function __construct($user = null) {
        $this->user = $user;
    }

    public function hasAccess($requiredRole) {
        return $this->user && $this->user['role'] >= $requiredRole;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
