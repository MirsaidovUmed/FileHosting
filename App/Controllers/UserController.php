<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;

class UserController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(Request $request): Response
    {
        $data = $request->getData();

        if (!isset($data["login"]) || !isset($data['password'])) {
            return new Response(['HTTP/1.1 400 Bad Request'], 'Missing login or password');
        }

        $login = $data['login'];
        $password = $data['password'];

        $success = $this->userService->createUser($login, $password);

        if ($success) {
            return new Response(['HTTP/1.1 201 Created'], 'User created successfully');
        } else {
            return new Response(['HTTP/1.1 500 Internal Server Error'], 'Failed to create user');
        }
    }

    public function updateUser(Request $request): Response
    {
        $data = $request->getData();

        if (!isset($data['id'])) {
            return new Response(['HTTP/1.1 400 Bad Request'], 'User ID is missing');
        }

        $userId = $data['id'];

        $existingUserData = $this->userService->findById($userId);

        $login = $data['login'] ?? $existingUserData['login'];
        $password = $data['password'] ?? $existingUserData['password'];

        $success = $this->userService->updateUser($userId, $login, $password);

        if ($success) {
            return new Response(['HTTP/1.1 200 Success'], 'User updated successfully');
        } else {
            return new Response(['HTTP/1.1 500 Internal Server Error'], 'Failed to update user');
        }
    }

    public function getUserById(Request $request): Response
    {
        $data = $request->getData();

        if (!isset($data['id'])) {
            return new Response(['HTTP/1.1 400 Bad Request'], 'User ID is missing');
        }

        $userId = $data['id'];

        $user = $this->userService->findById($userId);

        return new Response(['HTTP/1.1 200 OK', 'Content-Type: application/json'], json_encode($user));
    }

    public function deleteUser(Request $request): Response
    {
        $data = $request->getData();

        if (!isset($data['id'])) {
            return new Response(['HTTP/1.1 400 Bad Request'], 'User ID is missing');
        }

        $userId = $data['id'];

        $success = $this->userService->deleteUser($userId);

        if ($success) {
            return new Response(['HTTP/1.1 200 Success'], 'User deleted successfully');
        } else {
            return new Response(['HTTP/1.1 500 Internal Server Error'], 'Failed to delete user');
        }
    }
}