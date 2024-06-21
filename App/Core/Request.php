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

        if (str_starts_with($this->url, 'file/')) {
            $this->url = substr($this->url, 5);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];
        if (
            (
                $this->method == 'GET'
                && isset($_SERVER['CONTENT_TYPE'])
                && $_SERVER['CONTENT_TYPE'] == 'application/x-www-form-urlencoded'
            )
            || $this->method == 'PUT'
            || $this->method == 'DELETE'
        ) {
            parse_str(file_get_contents('php://input'), $this->params);
        } elseif ($this->method == "GET") {
            $this->params = $_GET;
        } elseif ($this->method == "POST") {
            $this->params = $_POST;
        }

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
