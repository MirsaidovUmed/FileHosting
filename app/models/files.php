<?php

namespace App\Models;

use DateTime;

class Files
{
    private int $id;
    private string $name;
    private int $folderId;
    private string $extension;
    private float $size;
    private int $userId;
    private DateTime $createdDate;

    public function __construct(?int $id, string $name, int $folderId, string $extension, float $size, int $userId, ?DateTime $createdDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->folderId = $folderId;
        $this->extension = $extension;
        $this->size = $size;
        $this->userId = $userId;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getFolderId(): int
    {
        return $this->folderId;
    }

    public function setFolderId(int $folderId): void
    {
        $this->folderId = $folderId;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function setSize(float $size): void
    {
        $this->size = $size;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
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
            'name' => $this->name,
            'folde_id' => $this->folderId,
            'extension' => $this->extension,
            'size' => $this->size,
            'user_id' => $this->userId,
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
            $data['name'] ?? null,
            $data['folder_id'] ?? null,
            $data['extension'] ?? null,
            $data['size'] ?? null,
            $data['user_id'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true));
    }
}
