<?php

namespace src\Core;

class Request
{
    public array $data = [];
    public string $method;
    public string $url;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = $_SERVER['REQUEST_URI'];
        $this->data = array_merge($_GET, $_POST);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}