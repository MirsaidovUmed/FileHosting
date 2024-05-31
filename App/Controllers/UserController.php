<?php

namespace App\Controllers;

use App\Core\App;
use App\Core\Request;
use App\Core\Response;
use App\Services\UserService;
use Exception;

class UserController extends BaseController
{
    protected UserService $userService;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->userService = $this->getService('User');
    }
//    protected UserService $userService;

//    public function __construct(UserService $userService)
//    {
//        $this->userService = $userService;
//    }

    /**
     * @throws Exception
     */
    public function createUser(Request $request): Response
    {
        $data = $request->getParams();

        if (!isset($data["login"]) || !isset($data['password'])) {
            return Response::setError(400, 'Missing login or password');
        }

        $login = $data['login'];
        $password = $data['password'];
        $role = $data['role'];

        $userService = $this->getService('User');
        $success = $userService->createUser($login, $password, $role);

        if ($success) {
            return Response::setOK('User created successfully');
        } else {
            return Response::setError(500, 'Failed to create user');
        }
    }

    /**
     * @throws Exception
     */
    public function updateUser(Request $request): Response
    {
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return Response::setError(400, 'User ID is missing');
        }

        $userId = $data['id'];

        $userService = $this->getService('User');
        $existingUserData = $userService->findById($userId);
        if (!$existingUserData) {
            return Response::setError(404, 'User not found');
        }

        $login = $data['login'] ?? $existingUserData['login'];
        $password = $data['password'] ?? $existingUserData['password'];

        $success = $userService->updateUser($userId, $login, $password);

        if ($success) {
            return Response::setOK('User updated successfully');
        } else {
            return Response::setError(500, 'Failed to update user');
        }
    }

    /**
     * @throws Exception
     */
    public function getUserById(Request $request): Response
    {
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return Response::setError(400, 'User ID is missing');
        }

        $userId = $data['id'];
        $userService = $this->getService('User');
        $user = $userService->findById($userId);

        if ($user) {
            return Response::setData(json_encode($user));
        } else {
            return Response::setError(404, 'User not found');
        }
    }

    /**
     * @throws Exception
     */
    public function deleteUser(Request $request): Response
    {
        $data = $request->getParams();

        if (!isset($data['id'])) {
            return Response::setError(400, 'User ID is missing');
        }

        $userId = $data['id'];
        $userService = $this->getService('User');
        $success = $userService->deleteUser($userId);

        if ($success) {
            return Response::setOK('User deleted successfully');
        } else {
            return Response::setError(500, 'Failed to delete user');
        }
    }
}
