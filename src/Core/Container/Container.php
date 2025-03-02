<?php

namespace Bibo\Core\Container;

use Closure;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Simple Dependency Injection (DI) container implementing PSR-11
 */
class Container implements ContainerInterface
{
    /**
     * Services stored in the container
     *
     * @var array
     */
    private array $bindings = [];
    private array $instances = [];

    /**
     * Bind a service to the container
     *
     * @param string  $id
     * @param Closure $concrete
     *
     * @return ContainerInterface
     */
    public function set(string $id, Closure $concrete): self
    {
        $this->bindings[$id] = $concrete;
        return $this;
    }

    /**
     * Get a service from the container
     *
     * @param string $id
     *
     * @return mixed
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->bindings[$id])) {
            throw new class ('Service ' . $id . ' not found') extends Exception implements ContainerExceptionInterface {
            };
        }

        $this->instances[$id] = $this->bindings[$id]($this);

        return $this->instances[$id];
    }

    /**
     * Check if a service exists in the container
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || isset($this->instances[$id]);
    }
}
