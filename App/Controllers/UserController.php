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
     * @throws Exception
     */
    protected function initializeServices(): void
    {
        $this->userService = $this->getService(UserService::class);
    }

    /**
     * @throws Exception
     */
    public function createUser(Request $request): Response
    {
        $this->initializeServices();
        $data = $request->getParams();

        if (!isset($data['login']) || !isset($data['password'])) {
            return $this->errorResponse('Missing login or password', 400);
        }

        $success = $this->userService->createUser($data['login'], $data['password'], $data['role'] ?? 'user');

        if ($success) {
            return $this->jsonResponse(['message' => 'User created successfully'], 201);
        } else {
            return $this->errorResponse('Failed to create user', 500);
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

        $success = $this->userService->updateUser($data['id'], $data['login'] ?? null, $data['password'] ?? null, $data['role'] ?? null);

        if ($success) {
            return $this->jsonResponse(['message' => 'User updated successfully']);
        } else {
            return $this->errorResponse('Failed to update user', 500);
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
