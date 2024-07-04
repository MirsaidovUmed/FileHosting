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
    /**
     * @throws Exception
     */
    public function createUser(Request $request, UserService $userService): Response
    {
        try {
            $userService->createUser($request->getParams());
            return $this->jsonResponse(['message' => 'Пользователь успешно создан'], 200);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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
            return $this->jsonResponse(['message' => 'Пользователь успешно обновлен']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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

            if ($user instanceof User) {
                return $this->jsonResponse(['user' => $user]);
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
            $userDeleted = $userService->deleteUser($data['id']);

            if ($userDeleted) {
                return $this->jsonResponse(['message' => 'Пользователь успешно удалён']);
            } else {
                return $this->errorResponse('Не удалось удалить пользователя', 500);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
