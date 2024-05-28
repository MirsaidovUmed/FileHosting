<?php

namespace App\Core;

class Web
{
    const URL_LIST = [
      'user' => [
          'GET' => 'User::showUser',
          'POST' => 'User::createUser'
      ]
    ];
}

