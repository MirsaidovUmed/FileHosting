<?php

namespace App\Services;

use App\Core\Interfaces\UserInterface;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Repositories\TokenRepository;
use Exception;

class AuthService
{
    private UserRepository $userRepository;
    private TokenRepository $tokenRepository;

    public function __construct(UserRepository $userRepository, TokenRepository $tokenRepository)
    {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
    }

    public function authenticate(string $login, string $password): ?User
    {
        $user = $this->userRepository->findOneBy(['login' => $login]);
        if ($user instanceof User && password_verify($password, $user->getPassword())) {
            return $user;
        }
        return null;
    }

    public function authorize(UserInterface $user, int $requiredRoleId): bool
    {
        return $user->getRoleId() === $requiredRoleId;
    }

    public function generateToken(UserInterface $user): string
    {
        $token = bin2hex(random_bytes(16));
        $this->tokenRepository->createToken($user->getId(), $token);
        return $token;
    }

    public function getUserByToken(string $token): ?User
    {
        $tokenModel = $this->tokenRepository->findByToken($token);
        if ($tokenModel) {
            return $this->userRepository->find($tokenModel->getUserId());
        }
        return null;
    }

    public function logout(string $token): void
    {
        $this->tokenRepository->deleteToken($token);
    }
}
