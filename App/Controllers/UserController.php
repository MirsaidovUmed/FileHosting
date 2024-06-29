<?php

namespace App\Controllers;

use App\Core\AbstractClasses\BaseController;
use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;
use Exception;

class UserController extends BaseController
{
    protected UserService $userService;

    /**
     * Метод для инициализации сервисов
     * @throws Exception
     */
    protected function initializeServices(): void
    {
        $this->userService = $this->app->getService(UserService::class);
    }

    /**
     * @throws Exception
     */
    public function createUser(Request $request): Response
    {
        $this->initializeServices();
        $data = $request->getParams();

        try {
            $this->userService->createUser($data);
            return $this->jsonResponse(['message' => 'User created successfully'], 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * @throws Exception
     */
    public function updateUser(Request $request): Response
    {
        $this->initializeServices();
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return $this->errorResponse('User ID is missing', 400);
        }

        try {
            $this->userService->updateUser($data['id'], $data);
            return $this->jsonResponse(['message' => 'User updated successfully']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * @throws Exception
     */
    public function getUserById(Request $request): Response
    {
        $this->initializeServices();
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return $this->errorResponse('User ID is missing', 400);
        }

        $user = $this->userService->findById($data['id']);

        if ($user) {
            return $this->jsonResponse(['user' => $user]);
        } else {
            return $this->errorResponse('User not found', 404);
        }
    }

    /**
     * @throws Exception
     */
    public function deleteUser(Request $request): Response
    {
        $this->initializeServices();
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return $this->errorResponse('User ID is missing', 400);
        }

        $success = $this->userService->deleteUser($data['id']);

        if ($success) {
            return $this->jsonResponse(['message' => 'User deleted successfully']);
        } else {
            return $this->errorResponse('Failed to delete user', 500);
        }
    }
}
