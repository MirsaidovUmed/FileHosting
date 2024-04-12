<?php

namespace App\Repositories;

use App\Models\Folders;
use App\Core\Database;
use PDO;

class FoldersRepository
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function finbById(int $id): ?Folders
    {
        $query = "SELECT * FROM folders WHERE id = :id";
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute(['id' => $id]);
        $foldersData = $statement->fetch(PDO::FETCH_ASSOC);

        return $foldersData ? new Folders(
            $foldersData['id'],
            $foldersData['name'],
            $foldersData['parrent_folder_id'],
            $foldersData['created_date'],
        ) : null;
    }

    public function finbByName(string $name): ?Folders
    {
        $query = 'SELECT * FROM folders WHERE name = :name';
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute(['name' => $name]);
        $foldersData = $statement->fetch(PDO::FETCH_ASSOC);

        return $foldersData ? new Folders(
            $foldersData['id'],
            $foldersData['name'],
            $foldersData['parrent_folder_id'],
            $foldersData['created_date'],
        ) : null;
    }

    public function save(Folders $folder): bool
    {
        $query = 'INSERT INTO folders (name, parrent_folder_id) VALUES (:name, :parrent_folder_id)';
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute([
            'name' => $folder->getName(),
            'parrent_folder_id' => $folder->getParrentFolderId(),
        ]);
        return $success;
    }

    public function update(Folders $folder): bool
    {
        $query = 'UPDATE folders SET name = :name, parrent_folder_id = :parrent_folder_id WHERE id = :id';
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute([
            'name' => $folder->getName(),
            'parrent_folder_id' => $folder->getParrentFolderId(),
        ]);

        return $success;
    }

    public function delete(Folders $folder): bool
    {
        $query = 'DELETE FROM folders WHERE id = :id';
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute(['id' => $folder->getId()]);

        return $success;
    }
}