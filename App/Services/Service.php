<?php

namespace App\Services;

use Exception;

abstract class Service
{
    protected array $repositories = [];

    public function __construct()
    {
        $this->initializeRepositories();
    }

    abstract protected function initializeRepositories(): void;

    /**
     * @throws Exception
     */
    protected function getRepository(string $repositoryName): mixed
    {
        if (!isset($this->repositories[$repositoryName])) {
            throw new Exception("Репозиторий не найден: " . $repositoryName);
        }
        return $this->repositories[$repositoryName];
    }
}