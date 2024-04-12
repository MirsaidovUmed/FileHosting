<?php

namespace App\Models;

use DateTime;

class Shares
{
    private int $id;
    private int $userId;
    private int $fileId;
    private DateTime $createdDate;

    public function __construct(?int $id, int $userId, int $fileId, ?DateTime $createdDate)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->createdDate = $createdDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getFileId()
    {
        return $this->fileId;
    }

    public function setFileId(int $fileId): void
    {
        $this->fileId = $fileId;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'file_id' => $this->fileId,
            'created_date' => $this->createdDate->format('Y-m-d H:i:s'),
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['user_id'] ?? null,
            $data['file_id'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true));
    }
}