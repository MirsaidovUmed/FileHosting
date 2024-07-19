<?php

namespace App\Repositories;

use App\Core\DB\Repository;
use App\Models\Token;

class TokenRepository extends Repository
{
    protected static function getModelClass(): string
    {
        return Token::class;
    }

    public function findByToken(string $token): ?Token
    {
        $data = $this->findOneBy(['token' => $token]);
        return $data ? $this->deserialize($data) : null;
    }

    public function createToken(int $userId, string $token): Token
    {
        $tokenModel = new Token();
        $tokenModel->setUserId($userId);
        $tokenModel->setToken($token);
        $this->save($tokenModel);

        return $tokenModel;
    }

    public function deleteToken(string $token): void
    {
        $tokenModel = $this->findByToken($token);
        if ($tokenModel) {
            $this->delete($tokenModel);
        }
    }
}
