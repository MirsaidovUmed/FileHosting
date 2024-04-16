<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Core\Request;
use App\Core\Response;

class UserController
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUserById(Request $request): Response
    {
        $userId = (int) $request->getData()['id'];
        $user = $this->userService->getUserById($userId);

        $response = new Response();
        if ($user) {
            $response->setData($user->toJson());
            $response->setHeaders(['HTTP/1.1 200 OK']);
        } else {
            $response->setData('User not found');
            $response->setHeaders(['HTTP/1.1 404 Not Found']);
        }

        return $response;
    }

    public function createUser(Request $request): Response
    {
        $requestData = $request->getData();
        $login = $requestData['login'] ?? null;
        $password = $requestData['password'] ?? null;

        if ($login === null || $password === null) {
            $response = new Response();
            $response->setData('Missing login or password');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        $success = $this->userService->createUser($login, $password);

        $response = new Response();
        if ($success) {
            $response->setData('User created successfully');
            $response->setHeaders(['HTTP/1.1 201 Created']);
        } else {
            $response->setData('Failed to create user');
            $response->setHeaders(['HTTP/1.1 500 Internal Server Error']);
        }

        return $response;
    }

    public function updateUser(Request $request): Response
    {
        $requestData = $request->getData();
        $userId = (int) ($requestData['id'] ?? 0);
        $login = $requestData['login'] ?? null;
        $password = $requestData['password'] ?? null;

        if ($userId === 0) {
            $response = new Response();
            $response->setData('Missing user id');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        $user = $this->userService->getUserById($userId);
        if (!$user) {
            $response = new Response();
            $response->setData('User not found');
            $response->setHeaders(['HTTP/1.1 404 Not Found']);
            return $response;
        }

        if ($login !== null) {
            $user->setLogin($login);
        }
        if ($password !== null) {
            $user->setPassword($password);
        }

        $success = $this->userService->updateUser($user);

        $response = new Response();
        if ($success) {
            $response->setData('User updated successfully');
            $response->setHeaders(['HTTP/1.1 200 OK']);
        } else {
            $response->setData('Failed to update user');
            $response->setHeaders(['HTTP/1.1 500 Internal Server Error']);
        }

        return $response;
    }
    public function deleteUser(Request $request): Response
    {
        $requestData = $request->getData();
        $userId = (int) ($requestData['id'] ?? 0);

        if ($userId === 0) {
            $response = new Response();
            $response->setData('Missing user id');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        $user = $this->userService->getUserById($userId);
        if (!$user) {
            $response = new Response();
            $response->setData('User not found');
            $response->setHeaders(['HTTP/1.1 404 Not Found']);
            return $response;
        }

        $success = $this->userService->deleteUser($user);

        $response = new Response();
        if ($success) {
            $response->setData('User deleted successfully');
            $response->setHeaders(['HTTP/1.1 200 OK']);
        } else {
            $response->setData('Failed to delete user');
            $response->setHeaders(['HTTP/1.1 500 Internal Server Error']);
        }

        return $response;
    }
}
