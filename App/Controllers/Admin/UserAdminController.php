<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use Exception;

class UserAdminController
{
    private UserRepository $userRepository;
    private AuthService $authService;

    public function __construct(UserRepository $userRepository, AuthService $authService)
    {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    public function listUsers(Request $request): Response
    {
        $users = $this->userRepository->findAll();
        return new Response(json_encode($users), 200);
    }

    public function getUser(Request $request, int $id): Response
    {
        $user = $this->userRepository->findById($id);
        if ($user) {
            return new Response(json_encode($user), 200);
        }
        return new Response('Пользователь не найден', 404);
    }

    public function deleteUser(Request $request, int $id): Response
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return new Response('Пользователь не найден', 404);
            }
            $this->userRepository->delete($user);
            return new Response('Пользователь удален', 200);
        } catch (Exception $e) {
            return new Response('Ошибка при удалении пользователя: ' . $e->getMessage(), 500);
        }
    }

    public function updateUser(Request $request, int $id): Response
    {
        $data = $request->getParams();
        try {
            $user = $this->userRepository->findById($id);
            if (!$user) {
                return new Response('Пользователь не найден', 404);
            }

            if (isset($data['login'])) {
                $user->setLogin($data['login']);
            }
            if (isset($data['password'])) {
                $user->setPassword($data['password']);
            }
            if (isset($data['roleId'])) {
                $user->setRoleId($data['roleId']);
            }

            $this->userRepository->save($user);
            return new Response('Пользователь обновлен', 200);
        } catch (Exception $e) {
            return new Response('Ошибка при обновлении пользователя: ' . $e->getMessage(), 500);
        }
    }
}
