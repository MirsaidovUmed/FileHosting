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

    public static function getValidationRules(string $method): array
    {
        return match ($method) {
            'login' => [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            default => throw new Exception("Метод не существует"),
        };
    }

    public static function getRequiredRole(string $method): int
    {
        return match ($method) {
            'login' => User::ROLE_GUEST,
            default => throw new Exception("Такой роли не существует"),
        };
    }

    public function login(Request $request): Response
    {
        $data = $request->getParams();

        try {
            $user = $this->authService->authenticate($data['email'], $data['password']);

            if ($user) {
                return $this->jsonResponse(['message' => 'Успешный вход', 'user' => $user], 200);
            } else {
                return $this->errorResponse('Неправильный email или пароль', 401);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
