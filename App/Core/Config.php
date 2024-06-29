<?php

namespace App\Core;

use Exception;

class Config
{
    private array $config;

    const SERVICES = 'services';
    const REPOSITORIES = 'repositories';
    const DATABASE = 'database';

    /**
     * @throws Exception
     */
    public function load(string $configFile): void
    {
        $configData = file_get_contents($configFile);
        $this->config = json_decode($configData, true);
        $this->validate();
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Метод для валидации конфигурации
     *
     * @throws Exception
     */
    private function validate(): void
    {
        $requiredKeys = [self::SERVICES, self::REPOSITORIES, self::DATABASE];
        foreach ($requiredKeys as $key) {
            if (!isset($this->config[$key])) {
                throw new Exception("Отсутствует обязательный ключ конфигурации: $key");
            }
        }

        if (!is_array($this->config[self::SERVICES])) {
            throw new Exception("Ключ 'services' должен быть массивом");
        }

        if (!is_array($this->config[self::REPOSITORIES])) {
            throw new Exception("Ключ 'repositories' должен быть массивом");
        }

        if (!is_array($this->config[self::DATABASE])) {
            throw new Exception("Ключ 'database' должен быть массивом");
        }
    }
}
