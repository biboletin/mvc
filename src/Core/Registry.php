<?php

namespace Biboletin\Mvc\Core;

/**
 *
 */
final class Registry
{
    /**
     * Registry container
     *
     * @var array
     */
    private array $registry;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set key => val in container
     *
     * @param string $key
     * @param        $value
     *
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->registry[$key] = $value;
    }

    /**
     * Get value by key if key is stored in container
     *
     * @param string $key
     * @param        $default
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null): mixed
    {
        if (!isset($this->registry[$key])) {
            return $default ?? null;
        }
        return $this->registry[$key];
    }

    /**
     * Check if key exists in container
     *
     * @param string $key
     *
     * @return bool
     */
    public function contains(string $key): bool
    {
        if (!isset($this->registry[$key])) {
            return false;
        }
        return true;
    }

    /**
     * Remove value by key
     *
     * @param string $key
     *
     * @return bool
     */
    public function remove(string $key): bool
    {
        if (!isset($this->registry[$key])) {
            return false;
        }
        unset($this->registry[$key]);
        return true;
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->registry = [];
    }
}
