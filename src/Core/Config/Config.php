<?php

namespace Bibo\Core\Config;

use Bibo\Core\Interfaces\ConfigInterface;
use RuntimeException;

/**
 * Config
 */
class Config implements ConfigInterface
{
    /**
     * Configuration array
     *
     * @var array
     */
    private array $config = [];

    /**
     * Config constructor
     *
     * Initializes the configuration array.
     */
    public function __construct()
    {
        $this->config = [];
    }

    /**
     * Get a configuration value
     *
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    public function get(string $key, ?string $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set a configuration value
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    /**
     * Check if a configuration key exists
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Get all configuration values
     *
     * @return array
     */
    public function all(): array
    {
        return !empty($this->config) ? $this->config : [];
    }

    /**
     * Load a configuration file
     *
     * @param string $file
     *
     * @return void
     * @throws RuntimeException
     */
    public function load(string $file): void
    {
        $key = basename($file, '.php');
        $content = require($file);

        if (!is_array($content)) {
            throw new RuntimeException('Configuration file ' . $file . ' must return an array');
        }

        $this->config[$key] = $content;
    }

    /**
     * Merge configuration values
     *
     * @param array $config
     *
     * @return void
     */
    public function merge(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Remove a configuration value
     *
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->config[$key]);
    }

    /**
     * Parse the config from environment variables
     *
     * @return void
     */
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
