<?php

namespace App\Services;

use App\Core\AbstractClasses\Service;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Core\Validator;
use Exception;

class UserService extends Service
{
    private UserRepository $userRepository;
    private Validator $validator;

    public function __construct(UserRepository $userRepository, Validator $validator)
    {
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        parent::__construct();
    }


    protected function initializeRepositories(): void
    {
        $this->repositories['User'] = $this->userRepository;
    }

    /**
     * @throws Exception
     */
    public function createUser(array $data): bool
    {
        $rules = [
            'login' => ['required', 'minLength:10'],
            'password' => ['required', 'minLength:8'],
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
    public function updateUser(int $userId, ?string $login = null, ?string $password = null, ?string $role = null): bool
    {
        $user = $this->userRepository->findById($userId);

        if ($user) {
            if ($login !== null) {
                $user->setLogin($login);
            }

            if ($password !== null) {
                $user->setPassword($password);
            }

            if ($role !== null) {
                $user->setRole($role);
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
