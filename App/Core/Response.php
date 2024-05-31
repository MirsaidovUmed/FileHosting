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

    public static function setOK(string $responseData = 'OK'): Response
    {
        return new self(200, $responseData);
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
        http_response_code($this->responseCode);

        if ($this->responseCode == 200) {
            echo $this->responseData;
        } else {
            $response = [
                'statusMessage' => str_contains($this->responseData, ';') ? explode(';', $this->responseData) : $this->responseData
            ];
            echo json_encode($response);
        }
    }
}
