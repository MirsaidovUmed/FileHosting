<?php

namespace App\Core;

class Request
{
    private string $url;
    private string $method;
    private array $params;

    public function setRequestParams(): void
    {
        $urlParams = explode('?', $_SERVER['REQUEST_URI']);
        $this->url = $urlParams[0];
        if (str_starts_with($this->url, '/')) {
            $this->url = substr($this->url, 1);
        }
        $this->method = $_SERVER['REQUEST_METHOD'];
        if (($this->method == 'GET' && $_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded')
            || $this->method == 'PUT' || $this->method == 'DELETE') {
            $this->params = [];
            parse_str(file_get_contents('php://input'), $this->params);
        } else if ($this->method == "GET") {
            array_shift($urlParams);
            $this->params = $urlParams;
        } else if ($this->method == "POST") {
            $this->params = $_POST;
        }
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
