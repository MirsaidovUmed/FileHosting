<?php

namespace App\Controllers;

use App\Services\FilesService;
use App\Core\Request;
use App\Core\Response;

class FilesController
{

    protected $filesService;
    public function __construct(FilesService $filesService)
    {
        $this->filesService = $filesService;
    }

    public function getFileById(Request $request): Response
    {
        $fileId = (int) $request->getData()["id"];
        $file = $this->filesService->getFilesId($fileId);

        $response = new Response();
        if ($file) {
            $response->setData($file->toJson());
            $response->setHeaders(['HTTP/1.1 200 OK']);
        } else {
            $response->setData('User not found');
            $response->setHeaders(['HTTP/1.1 404 Not Found']);
        }

        return $response;
    }

    public function createFile(Request $request): Response
    {
        $requestData = $request->getData();
        $name = $requestData['name'] ?? null;
        $folderId = $requestData['folder_id'] ?? null;
        $extension = $requestData['extension'] ?? null;
        $size = $requestData['size'] ?? null;
        $userId = $requestData['user_id'] ?? null;

        if ($name === null || $folderId === null || $extension === null || $size === null || $userId === null) {
            $response = new Response();
            $response->setData('Missing login or password');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        $success = $this->filesService->createFiles($name, $folderId, $extension, $size, $userId);
        $response = new Response();
        if ($success) {
            $response->setData('File created successfully');
            $response->setHeaders(['HTTP/1.1 201 Created']);
        } else {
            $response->setData('Failed to create file');
            $response->setHeaders(['HTTP/1.1 500 Internal Server Error']);
        }

        return $response;
    }

    public function updateFiles(Request $request): Response
    {
        $requestData = $request->getData();
        $filesId = (int) ($requestData['id'] ?? 0);
        $name = $requestData['name'] ?? null;
        $folderId = $requestData['folder_id'] ?? null;
        $extension = $requestData['extension'] ?? null;
        $size = $requestData['size'] ?? null;
        $userId = $requestData['user_id'] ?? 0;

        if ($filesId === 0) {
            $response = new Response();
            $response->setData('Missong file id');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        if ($userId === 0) {
            $response = new Response();
            $response->setData('Missing user id');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        $file = $this->filesService->getFilesId($filesId);
        if (!$file) {
            $response = new Response();
            $response->setData('File not found');
            $response->setHeaders(['HTTP/1.1 404 Not Found']);
            return $response;
        }

        if ($name != null) {
            $file->setName($name);
        }
        if ($folderId != null) {
            $file->setFolderId($folderId);
        }
        if ($extension != null) {
            $file->setExtension($extension);
        }
        if ($size != null) {
            $file->setSize($size);
        }

        $success = $this->filesService->updateFiles($file);
        $response = new Response();
        if ($success) {
            $response->setData('File updated successfully');
            $response->setHeaders(['HTTP/1.1 200 OK']);
        } else {
            $response->setData('Failed to update file');
            $response->setHeaders(['HTTP/1.1 500 Internal Server Error']);
        }

        return $response;
    }

    public function deleteFiles(Request $request): Response
    {
        $requestData = $request->getData();
        $fileId = (int) ($requestData['id'] ?? 0);

        if ($fileId === 0) {
            $response = new Response();
            $response->setData('Missing user id');
            $response->setHeaders(['HTTP/1.1 400 Bad Request']);
            return $response;
        }

        $file = $this->filesService->getFilesId($fileId);
        if (!$file) {
            $response = new Response();
            $response->setData('File not found');
            $response->setHeaders(['HTTP/1.1 404 Not Found']);
            return $response;
        }

        $success = $this->filesService->deleteFiles($file);

        $response = new Response();
        if ($success) {
            $response->setData('File deleted successfully');
            $response->setHeaders(['HTTP/1.1 200 OK']);
        } else {
            $response->setData('Failed to delete file');
            $response->setHeaders(['HTTP/1.1 500 Internal Server Error']);
        }

        return $response;
    }
}