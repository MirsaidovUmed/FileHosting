<?php

namespace App\Models;

class Folders
{
    private $id;
    private $name;
    private $parrent_folder_id;
    private $created_date;

    public function __construct($id, $name, $parrent_folder_id, $created_date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parrent_folder_id = $parrent_folder_id;
        $this->created_date = $created_date;
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

    public function getParrentFolderId()
    {
        return $this->parrent_folder_id;
    }

    public function setParrentFolderId($parrent_folder_id)
    {
        $this->parrent_folder_id = $parrent_folder_id;
    }

    public function getCreatedDate()
    {
        return $this->created_date;
    }

    public function setCreatedDate($created_date)
    {
        $this->created_date = $created_date;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'parrent_folder_id'=> $this->parrent_folder_id,
            'created_date' => $this->created_date,
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
            $data['parrent_folder_id'] ?? null,
            $data['created_date'] ?? null
        );
    }

    public static function fromJson($json)
    {
        return self::fromArray(json_decode($json, true));
    }
}