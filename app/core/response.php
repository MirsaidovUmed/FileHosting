<?php

namespace App\Core;

class Response
{
    private string $data;
    private array $headers;

    public function __construct()
    {
        $this->data = '';
        $this->headers = [];
    }

    public function setData(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function send(): void
    {
        if (!headers_sent()) {
            foreach ($this->headers as $header) {
                header($header);
            }
        }

        echo $this->data;
    }
}
