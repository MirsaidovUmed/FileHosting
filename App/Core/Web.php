<?php

namespace App\Core;

class Web
{
    const URL_LIST = [
        'user/{id}' => [
            'GET' => 'UserController::getUserById',
            'PUT' => 'UserController::updateUser',
            'DELETE' => 'UserController::deleteUser',
        ],
        'user' => [
            'POST' => 'UserController::createUser',
        ],
        'user/list' => [
            'GET' => 'UserController::getUserList',
        ],
        'admin/users/list' =>[
            'GET'=> 'UserAdminController::getUserList',
        ]
    ];
}
