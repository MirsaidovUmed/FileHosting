<?php

namespace App\Core;

class Request
{
    public array $data = [];
    public string $method;
    public string $url;

    public function __construct()
    {
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        $this->url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
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