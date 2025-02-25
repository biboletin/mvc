<?php

namespace Bibo\Core\Interfaces;

/**
 * Config Interface
 */
interface ConfigInterface
{
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
    public function get(string $key, ?string $default = null): mixed;

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
    public function set(string $key, $value): void;

    /**
     * Check if a key exists in the config
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Get all the values from the config
     *
     * @return array
     */
    public function all(): array;

    /**
     * Load a config file
     *
     * @param string $file
     *
     * @return void
     */
    public function load(string $file): void;

    /**
     * Save the config to a file
     *
     * @param string $file
     *
     * @return void
     */
    public function save(string $file): void;

    /**
     * Merge an array into the config
     *
     * @param array $config
     *
     * @return void
     */
    public function merge(array $config): void;

    /**
     * Merge a file into the config
     *
     * @param string $file
     *
     * @return void
     */
    public function mergeFile(string $file): void;

    /**
     * Remove a key from the config
     *
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void;

    /**
     * Parse the config from an ini file
     *
     * @return void
     */
    public function parseFromIni(): void;

    /**
     * Parse the config from database config settings
     *
     * @return void
     */
    public function parseFromDb(): void;
}
