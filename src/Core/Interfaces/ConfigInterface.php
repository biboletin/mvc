<?php

namespace Bibo\Mvc\Core\Interfaces;

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
     * Load the config files from the config directory
     *
     * @return void
     */
    public function load(): void;

    /**
     * Load a config file
     *
     * @param string $file
     *
     * @return void
     */
    public function loadFromFile(string $file): void;
}
