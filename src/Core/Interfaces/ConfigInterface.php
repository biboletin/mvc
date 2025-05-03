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
    public static function get(string $key, ?string $default = null): mixed;

    /**
     * Check if a key exists in the config
     *
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key): bool;

    /**
     * Get all the values from the config
     *
     * @return array
     */
    public static function all(): array;

    /**
     * Load the config files from the config directory
     *
     * @return void
     */
    public static function load(): void;

    /**
     * Load a config file
     *
     * @param string $file
     *
     * @return void
     */
    public static function loadFromFile(string $file): void;
}
