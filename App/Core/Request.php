<?php

namespace App\Core;

class Request
{
    private string $url;
    private string $method;
    private array $params;

    public function __construct()
    {
        $this->setRequestParams();
    }

    public function setRequestParams(): void
    {
        $urlParams = explode('?', $_SERVER['REQUEST_URI']);
        $this->url = ltrim($urlParams[0], '/');

        if (str_starts_with($this->url, 'file/')) {
            $this->url = substr($this->url, 5);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];

        match ($this->method) {
            'GET' => $this->params = $_GET,
            'POST' => $this->params = $_POST,
            'PUT', 'DELETE' => parse_str(file_get_contents('php://input'), $this->params),
            default => $this->params = []
        };

        error_log("Request URL: " . $this->url);
        error_log("Request Method: " . $this->method);
        error_log("Request Params: " . json_encode($this->params));
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

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}
