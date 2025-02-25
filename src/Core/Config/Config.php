<?php

namespace Bibo\Core\Config;

use Bibo\Core\Interfaces\ConfigInterface;

/**
 * Config
 */
class Config implements ConfigInterface
{
    private array $config = [];

    /**
     * Get a value from the config
     * If the key does not exist, return the default value
     * If the default value is not set, return null
     *
     * @param string      $key
     * @param string|null $default
     *
     * @return mixed
     */
    public function get(string $key, ?string $default = null): mixed
    {
        // TODO: Implement get() method.
    }

    /**
     * Set a value in the config
     * If the key already exists, overwrite it
     * If the key does not exist, create it
     * If the value is null, remove the key
     * If the value is an array, merge it with the existing value
     * If the value is a string, replace the existing value
     * If the value is an object, convert it to an array
     *
     * @param string $key
     * @param $value
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        // TODO: Implement set() method.
    }

    /**
     * Check if a key exists in the config
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        // TODO: Implement has() method.
    }

    /**
     * Get all the values from the config
     *
     * @return array
     */
    public function all(): array
    {
        // TODO: Implement all() method.
    }

    /**
     * Load a config file
     *
     * @param string $file
     *
     * @return void
     */
    public function load(string $file): void
    {
        // TODO: Implement load() method.
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
     * Merge an array into the config
     *
     * @param array $config
     *
     * @return void
     */
    public function merge(array $config): void
    {
        // TODO: Implement merge() method.
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
     * Remove a key from the config
     *
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        // TODO: Implement remove() method.
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
