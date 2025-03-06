<?php

namespace Bibo\Core\Config;

use Bibo\Core\Interfaces\ConfigInterface;

/**
 * Config
 */
class Config implements ConfigInterface
{
    private array $config = [];

    public function __construct()
    {
        $this->config = [];
    }

    public function get(string $key, ?string $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    public function all(): array
    {
        return !empty($this->config) ? $this->config : [];
    }

    public function load(string $file): void
    {
        $key = basename($file, '.php');
        $content = require $file;

        if (!is_array($content)) {
            throw new \RuntimeException("Config file {$file} must return an array.");
        }

        $this->config[$key] = $content;
    }

    public function merge(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }

    public function remove(string $key): void
    {
        unset($this->config[$key]);
    }

    public function parseFromEnv(): void
    {
        foreach ($_ENV as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Save the config to a file
     *
     * @param string $file
     *
     * @return void
     */
    public function save(string $file): void
    {
        // TODO: Implement save() method.
    }

    /**
     * Merge a file into the config
     *
     * @param string $file
     *
     * @return void
     */
    public function mergeFile(string $file): void
    {
        // TODO: Implement mergeFile() method.
    }

    /**
     * Parse the config from an ini file
     *
     * @return void
     */
    public function parseFromIni(): void
    {
        // TODO: Implement parseFromIni() method.
    }

    /**
     * Parse the config from database config settings
     *
     * @return void
     */
    public function parseFromDb(): void
    {
        // TODO: Implement parseFromDb() method.
    }
}
