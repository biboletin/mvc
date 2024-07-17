<?php

namespace Biboletin\Mvc\Core\DIContainer;

use Exception;

/**
 * Container class
 */
class Container implements DIContainerInterface
{
    /**
     * Array of factories
     *
     * @var array<string, mixed>
     */
    private array $container = [];

    /**
     * Set a factory
     *
     * @param string   $factoryId
     * @param callable $factory
     *
     * @return void
     */
    public function set(string $factoryId, callable $factory): void
    {
        $this->container[$factoryId] = $factory;
    }

    /**
     * Get a factory
     *
     * @throws Exception
     */
    public function get(string $factoryId): mixed
    {
        if (isset($this->container[$factoryId]) === false) {
            throw new Exception('Binding not exists!');
        }
        $factory = $this->container[$factoryId];
        return $factory($this);
    }

    /**
     * Check if a factory exists
     *
     * @param string $factoryId
     *
     * @return bool
     */
    public function has(string $factoryId): bool
    {
        return isset($this->container[$factoryId]);
    }
    /**
     * Remove a factory
     *
     * @param string $factoryId
     *
     * @return void
     */
    public function remove(string $factoryId): void
    {
        if (isset($this->container[$factoryId]) === true) {
            unset($this->container[$factoryId]);
        }
    }
}
