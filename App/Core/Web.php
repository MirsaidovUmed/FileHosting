<?php

namespace App\Core;

class Web
{
    const URL_LIST = [
        'user' => [
            'GET' => 'UserController::getUserById',
            'POST' => 'UserController::createUser',
            'PUT' => 'UserController::updateUser',
            'DELETE' => 'UserController::deleteUser',
        ]
    ];
}
