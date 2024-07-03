<?php

namespace App\Services;

use App\Core\Validator;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;
    private Validator $validator;

    public function __construct(UserRepository $userRepository, Validator $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function createUser(array $data): bool
    {
        $rules = [
            'login' => ['required', 'minLength:3'],
            'password' => ['required', 'minLength:6'],
            'role' => ['required']
        ];

        if (!$this->validator->validate($data, $rules)) {
            throw new Exception(json_encode($this->validator->getErrors(), JSON_UNESCAPED_UNICODE));
        }

        $user = new User();
        $user->setLogin($data['login']);
        $user->setPassword($data['password']);
        $user->setRole($data['role']);
        return $this->userRepository->createUser($user);
    }

    /**
     * @throws Exception
     */
    public function updateUser(int $userId, array $data): bool
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            if (isset($data['login'])) {
                $user->setLogin($data['login']);
            }

            if (isset($data['password'])) {
                $user->setPassword($data['password']);
            }

            if (isset($data['role'])) {
                $user->setRole($data['role']);
            }
            return $this->userRepository->updateUser($userId, $user);
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function findById(int $userId): ?User
    {
        return $this->userRepository->findById($userId);
    }

    /**
     * @throws Exception
     */
    public function deleteUser(int $userId): bool
    {
        return $this->userRepository->deleteUser($userId);
    }
}
