<?php

namespace Config;

class Config
{
    private array $config;

    public function load(string $configFile): void
    {
        $configData = file_get_contents($configFile);
        $this->config = json_decode($configData, true);
    }

    public function get(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }
}