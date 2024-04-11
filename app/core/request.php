<?php

namespace App\Core;

class Request
{
    private $data = [];
    private $route = '';
    private $method = '';

    public function __construct()
    {
        $this->data = $_POST;
        $this->route = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function getData()
    {
        return $this->data;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
