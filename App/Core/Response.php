<?php

namespace App\Core;

class Response
{
    private int $responseCode;
    private string $responseData;

    public function __construct(int $responseCode, string $responseData)
    {
        $this->responseCode = $responseCode;
        $this->responseData = $responseData;
    }

    public static function setOK(): Response
    {
        return new self(200, 'OK');
    }

    public static function setData(string $responseData): Response
    {
        return new self(200, $responseData);
    }

    public static function setError(int $responseCode, string $responseData): Response
    {
        return new self($responseCode, $responseData);
    }

    public function sendResponse(): void
    {
        if ($this->responseCode == 200) {
            echo $this->responseData;
        } else {
            http_response_code($this->responseCode);
            if (!str_contains($this->responseData, ';')) {
                echo json_encode(array('statusMessage' => $this->responseData));
            } else {
                echo json_encode(array('statusMessage' => explode(';', $this->responseData)));
            }
        }
    }
}
