<?php

namespace Biboletin\Mvc\Core;

use ArrayAccess;

/**
 * Registry class
 */
class Registry implements ArrayAccess
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
     * @param $value
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
     * @param $default
     *
     * @return mixed|null
     */
    public function get(string $key, $default = null): mixed
    {
        return $this->registry[$key] ?? $default;
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
        return isset($this->registry[$key]);
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
        if (isset($this->registry[$key]) === false) {
            return false;
        }
        unset($this->registry[$key]);
        return true;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->registry[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->registry[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->registry[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->registry[$offset]);
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->registry = [];
    }
}
