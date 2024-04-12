<?php

class Files
{
    private $id;
    private $name;
    private $folderId;
    private $extention;
    private $size;
    private $userId;
    private $createdDate;

    public function __construct($id, $name, $folderId, $extention, $size, $userId, $createdDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->folderId = $folderId;
        $this->extention = $extention;
        $this->size = $size;
        $this->userId = $userId;
        $this->createdDate = $createdDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getFolderId()
    {
        return $this->folderId;
    }

    public function setFolderId($folderId)
    {
        $this->folderId = $folderId;
    }

    public function getExtention()
    {
        return $this->extention;
    }

    public function setExtention($extention)
    {
        $this->extention = $extention;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'folde_id' => $this->folderId,
            'extention' => $this->extention,
            'size' => $this->size,
            'user_id' => $this->userId,
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
            $data['name'] ?? null,
            $data['folder_id'] ?? null,
            $data['extension'] ?? null,
            $data['size'] ?? null,
            $data['user_id'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson($json)
    {
        return self::fromArray(json_decode($json, true));
    }
}
