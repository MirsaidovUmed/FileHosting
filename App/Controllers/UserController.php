<?php

namespace App\Controllers;

use App\Core\AbstractClasses\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use App\Services\UserService;
use Exception;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public static function getValidationRules(string $method): array
    {
        return match ($method) {
            'createUser' => [
                'login' => ['required', 'minLength:3'],
                'password' => ['required', 'minLength:6'],
                'role_id' => ['required'],
            ],
            'updateUser' => [
                'id' => ['required', 'integer'],
                'login' => ['required', 'minLength:3'],
                'password' => ['required', 'minLength:6'],
                'role_id' => ['required'],
            ],
            'getUserList' => [
                'limit' => ['required', 'integer'],
                'offset' => ['required', 'integer'],
            ],
            default => throw new Exception("Метод не существует"),
        };
    }

    public static function getRequiredRole(string $method): int
    {
        return match ($method) {
            'createUser', 'updateUser', 'deleteUser' => User::ROLE_ADMIN,
            'getUserList', 'getUserById' => User::ROLE_USER,
            default => throw new Exception("Такой роли не существует"),
        };
    }

    public function createUser(Request $request): Response
    {
        try {
            $this->userService->createUser($request->getParams());
            return $this->jsonResponse(['message' => 'Пользователь успешно создан'], 200);
        } catch (Exception $e) {
            $errorData = json_decode($e->getMessage(), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->jsonResponse(['errors' => $errorData], 400);
            } else {
                return $this->errorResponse($e->getMessage(), 400);
            }
        }
    }

    public function updateUser(Request $request): Response
    {
        $data = $request->getParams();

        try {
            $this->userService->updateUser($data['id'], $data);
            return $this->jsonResponse(['message' => 'Пользователь успешно обновлен'], 200);
        } catch (Exception $e) {
            $errorData = json_decode($e->getMessage(), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $this->jsonResponse(['errors' => $errorData], 400);
            } else {
                return $this->errorResponse($e->getMessage(), 400);
            }
        }
    }

    public function getUserById(Request $request): Response
    {
        $data = $request->getParams();

        try {
            $user = $this->userService->findById($data['id']);

            if ($user) {
                return $this->jsonResponse(['user' => $user], 200);
            } else {
                return $this->errorResponse('Пользователь не найден', 404);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function getUserList(Request $request): Response
    {
        $data = $request->getParams();

        try {
            $users = $this->userService->findAll($data['limit'], $data['offset']);
            return $this->jsonResponse(['users' => $users], 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function deleteUser(Request $request): Response
    {
        $data = $request->getParams();

        try {
            $this->userService->deleteUser($data['id']);
            return $this->jsonResponse(['message' => 'Пользователь успешно удалён'], 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
