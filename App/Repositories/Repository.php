<?php

namespace App\Repositories;

use App\Core\Database;
use Config\Config;
use Exception;

abstract class Repository
{
    protected static ?Config $config = null;
    protected static ?Database $database = null;

    public function __construct()
    {
        if (self::$database === null) {
            self::$database = Database::getInstance(self::$config->get('database'));
        }
    }

    /**
     * @throws Exception
     */
    public static function getInstance(): static
    {
        if (self::$config === null) {
            throw new Exception("Конфигурация не инициализирована");
        }

        $class = static::class;
        if (!class_exists($class)) {
            throw new Exception("Репозиторий не найден: " . $class);
        }

        return new $class();
    }

    public static function setConfig(Config $config): void
    {
        self::$config = $config;
    }
}
