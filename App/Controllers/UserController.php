<?php

namespace App\Controllers;

use App\Core\AbstractClasses\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;
use Exception;

class UserController extends BaseController
{
    private static array $validationRules = [
        'login' => ['required', 'minLength:3'],
        'password' => ['required', 'minLength:6'],
        'role' => ['required']
    ];

    /**
     * @throws Exception
     */
    public function __construct(Request $request)
    {
        if (!$request->validate(self::$validationRules)) {
            throw new Exception(json_encode($request->getValidationErrors(), JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * @throws Exception
     */
    public function createUser(Request $request, UserService $userService): Response
    {
        try {
            $userService->createUser($request->getParams());
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

    /**
     * @throws Exception
     */
    public function updateUser(Request $request, UserService $userService): Response
    {
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return $this->errorResponse('Идентификатор пользователя отсутствует', 400);
        }

        try {
            $userService->updateUser($data['id'], $data);
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

    /**
     * @throws Exception
     */
    public function getUserById(Request $request, UserService $userService): Response
    {
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return $this->errorResponse('Идентификатор пользователя отсутствует', 400);
        }

        try {
            $user = $userService->findById($data['id']);

            if ($user) {
                return $this->jsonResponse(['user' => $user], 200);
            } else {
                return $this->errorResponse('Пользователь не найден', 404);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * @throws Exception
     */
    public function deleteUser(Request $request, UserService $userService): Response
    {
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return $this->errorResponse('Идентификатор пользователя отсутствует', 400);
        }

        try {
            $userService->deleteUser($data['id']);
            return $this->jsonResponse(['message' => 'Пользователь успешно удалён'], 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
