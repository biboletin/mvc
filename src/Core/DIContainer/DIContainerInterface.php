<?php

namespace Biboletin\Mvc\Core\DIContainer;

/**
 * DIContainerInterface
 */
interface DIContainerInterface
{
    /**
     * Set a factory
     *
     * @param string   $factoryId
     * @param callable $factory
     *
     * @return void
     */
    public function set(string $factoryId, callable $factory): void;

    /**
     * Get a factory
     *
     * @param string $factoryId
     *
     * @return mixed
     */
    public function get(string $factoryId): mixed;

    /**
     * Check if a factory exists
     *
     * @param string $factoryId
     *
     * @return bool
     */
    public function has(string $factoryId): bool;

    /**
     * Remove a factory
     *
     * @param string $factoryId
     *
     * @return void
     */
    public function remove(string $factoryId): void;
}
