<?php

namespace App\Models;

class Shares
{
    private $id;
    private $userId;
    private $fileId;
    private $createdDate;

    public function __construct($id, $userId, $fileId, $createdDate)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->fileId = $fileId;
        $this->createdDate = $createdDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getFileId()
    {
        return $this->fileId;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'file_id' => $this->fileId,
            'created_date' => $this->createdDate,
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public static function fromArray($data)
    {
        return new self(
            $data['id'] ?? null,
            $data['user_id'] ?? null,
            $data['file_id'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson($json)
    {
        return self::fromArray(json_decode($json, true));
    }
}