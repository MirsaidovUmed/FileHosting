<?php

namespace App\Services;

use App\Models\Files;
use App\Repositories\FilesRepository;

class FilesService
{
    protected $filesRepository;

    public function __construct(FilesRepository $filesRepository)
    {
        $this->filesRepository = $filesRepository;
    }

    public function getFilesId(int $id): ?Files
    {
        return $this->filesRepository->findById($id);
    }

    public function createFiles(string $name, string $folder_id, string $extension, float $size, int $userId): bool
    {
        $file = new Files(null, $name, $folder_id, $extension, $size, $userId, null);
        return $this->filesRepository->save($file);
    }

    public function updateFiles(Files $file): bool
    {
        return $this->filesRepository->update($file);
    }

    public function deleteFiles(Files $file): bool
    {
        return $this->filesRepository->delete($file);
    }
}