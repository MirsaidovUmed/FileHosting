<?php

namespace App\Core;

class Response
{
    protected array $headers;
    protected string $data;

    public function __construct(array $headers, string $data)
    {
        $this->headers = $headers;
        $this->data = $data;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function setData(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            foreach ($this->headers as $header) {
                header($header);
            }
        }
    }
}