<?php

namespace App\Repositories;

use App\Models\Shares;
use App\Core\Database;
use PDO;

class ShareRepository
{
    protected $db;
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?Shares
    {
        $query = "SELECT FROM shares WHERE id = :id";
        $statement = $this->db->getConnection()->prepare($query);
        $statement->execute(['id' => $id]);
        $shareData = $statement->fetch(PDO::FETCH_ASSOC);

        return $shareData ? new Shares(
            $shareData['id'],
            $shareData['user_id'],
            $shareData['file_id'],
            $shareData['created_date'],
        ) : null;
    }

    public function save(Shares $share): bool
    {
        $query = 'INSERT INTO shares (user_id, file_id) VALUES (:user_id, :files_id)';
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute([
            'user_id' => $share->getUserId(),
            'file_id' => $share->getFileId(),
        ]);

        return $success;
    }

    public function delete(Shares $share): bool
    {
        $query = 'DELETE FROM files WHERE id = :id';
        $statement = $this->db->getConnection()->prepare($query);
        $success = $statement->execute(['id' => $share->getId()]);

        return $success;
    }
}