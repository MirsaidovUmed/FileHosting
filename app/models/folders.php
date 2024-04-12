<?php

namespace App\Models;

use DateTime;

class Folders
{
    private int $id;
    private string $name;
    private int $parrentFolderId;
    private DateTime $created_date;

    public function __construct(int $id, string $name, int $parrentFolderId, DateTime $created_date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parrentFolderId = $parrentFolderId;
        $this->created_date = $created_date;
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

    public function getParrentFolderId(): int
    {
        return $this->parrentFolderId;
    }

    public function setParrentFolderId(int $parrentFolderId): void
    {
        $this->parrentFolderId = $parrentFolderId;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->created_date;
    }

    public function setCreatedDate(DateTime $created_date): void
    {
        $this->created_date = $created_date;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parrent_folder_id' => $this->parrentFolderId,
            'created_date' => $this->created_date,
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
            $data['parrent_folder_id'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson(string $json): self
    {
        return self::fromArray(json_decode($json, true));
    }
}