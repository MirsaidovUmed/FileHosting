<?php

namespace App\Core;

use Exception;

abstract class Service implements IService
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
    protected function getRepository(string $repositoryName): Repository
    {
        if (!isset($this->repositories[$repositoryName])) {
            throw new Exception("Репозиторий не найден: " . $repositoryName);
        }
        return $this->repositories[$repositoryName];
    }
}
