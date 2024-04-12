<?php

namespace App\Repositories;

use App\Models\Files;
use App\Core\Database;
use PDO;

class FilesRepository
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?Files
    {
        $query = "SELECT * FROM files WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->execute(['id' => $id]);
        $fileData = $statement->fetch(PDO::FETCH_ASSOC);

        return $fileData ? new Files(
            $fileData['id'],
            $fileData['name'],
            $fileData['folder_id'],
            $fileData['extension'],
            $fileData['size'],
            $fileData['user_id'],
            $fileData['created_date'],
        ) : null;
    }

    public function save(Files $files): bool
    {
        $query = "INSERT INTO files (name, folder_id, extension, size, user_id) VALUES (:name, :folder_id, :extension, :size, :user_id)";
        $statement = $this->db->prepare($query);
        $success = $statement->execute([
            'name' => $files->getName(),
            'folder_id' => $files->getFolderId(),
            'extension' => $files->getExtension(),
            'size' => $files->getSize(),
            'user_id' => $files->getUserId(),
        ]);
        return $success;
    }

    public function update(Files $files): bool
    {
        $query = 'UPDATE files SET name = :name, folder_id = :folder_id, extension = :extension, size = :size WHERE id = :id';
        $statement = $this->db->prepare($query);
        $success = $statement->execute([
            'name' => $files->getName(),
            'folder_id' => $files->getFolderId(),
            'extension' => $files->getExtension(),
            'size' => $files->getSize(),
        ]);
        return $success;
    }

    public function delete(Files $files): bool
    {
        $query = "DELETE FROM files WHERE id = :id";
        $statement = $this->db->prepare($query);
        $success = $statement->execute(["id" => $files->getId()]);

        return $success;
    }
}
