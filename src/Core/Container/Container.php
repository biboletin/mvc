<?php

namespace Bibo\Core\Container;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Exception;

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
    private array $services = [];

    /**
     * Bind a service to the container
     *
     * @param string   $id
     * @param callable $concrete
     *
     * @return ContainerInterface
     */
    public function set(string $id, callable $concrete): ContainerInterface
    {
        $this->services[$id] = $concrete;
        return $this;
    }

    /**
     * Get a service from the container
     *
     * @param  string $id
     * @return mixed
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new class ($id . ' not found in container') extends Exception implements NotFoundExceptionInterface {
            };
        }

        return $this->services[$id]($this);
    }

    /**
     * Check if a service exists in the container
     *
     * @param  string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
