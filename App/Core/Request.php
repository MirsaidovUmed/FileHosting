<?php

namespace App\Core;

use Exception;

class Request
{
    private string $url;
    private string $method;
    private array $params;
    private Validator $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
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

    /**
     * @throws Exception
     */
    public function validate(array $rules): bool
    {
        return $this->validator->validate($this->params, $rules);
    }

    public function getValidationErrors(): array
    {
        return $this->validator->getErrors();
    }
}
