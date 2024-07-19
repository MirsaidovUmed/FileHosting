<?php

namespace App\Controllers;

use App\Core\AbstractClasses\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;
use App\Models\User;
use Exception;

class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request): Response
    {
        $params = $request->getParams();
        $user = $this->authService->authenticate($params['login'], $params['password']);
        if ($user) {
            $token = $this->authService->generateToken($user);
            return $this->jsonResponse(['token' => $token], 200);
        } else {
            return $this->jsonResponse(['error' => 'Неверный логин или пароль'], 401);
        }
    }

    public function logout(Request $request): Response
    {
        $token = $request->getHeader('Authorization');
        if ($token) {
            $this->authService->logout($token);
            return $this->jsonResponse(['message' => 'Вы вышли из системы'], 200);
        } else {
            return $this->jsonResponse(['error' => 'Токен не предоставлен'], 400);
        }
    }

    public function checkAccess(Request $request, int $requiredRoleId): bool
    {
        $user = $this->getUserFromRequest($request);
        return $this->authService->authorize($user, $requiredRoleId);
    }

    private function getUserFromRequest(Request $request): ?User
    {
        $token = $request->getHeader('Authorization');
        if ($token) {
            return $this->authService->getUserByToken($token);
        }
        return null;
    }
}
