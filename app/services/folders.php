<?php

namespace App\Services;

use App\Models\Folders;
use App\Repositories\FoldersRepository;

class FoldersService
{
    protected $foldersRepository;

    public function __construct(FoldersRepository $foldersRepository)
    {
        $this->foldersRepository = $foldersRepository;
    }

    public function getFoldersId(int $id): ?Folders
    {
        return $this->foldersRepository->findById($id);
    }

    public function getFoldersName(string $name): ?Folders
    {
        return $this->foldersRepository->findByName($name);
    }

    public function createFolder(string $name, int $parrent_folder_id): bool
    {
        $folder = new Folders(null, $name, $parrent_folder_id, null);
        return $this->foldersRepository->save($folder);
    }

    public function updateFolder(Folders $folder)
    {
        return $this->foldersRepository->update($folder);
    }

    public function deleteFolder(Folders $folder): bool
    {
        return $this->foldersRepository->delete($folder);
    }
}