<?php

namespace Bibo\Mvc\Core\Traits;

use Bibo\Mvc\Core\Router\BaseRouter;
use Psr\Container\ContainerInterface;

/**
 * ContainerAwareTrait
 * Trait to provide a container instance to a class
 */
trait ContainerAwareTrait
{
    /**
     * Container instance
     *
     * @var ContainerInterface|null $container
     */
    protected ?ContainerInterface $container = null;

    /**
     * Set the container instance
     *
     * @param ContainerInterface $container
     *
     * @return ContainerAwareTrait|BaseRouter
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get the container instance
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Check if a container instance is set
     *
     * @return bool
     */
    public function hasContainer(): bool
    {
        return isset($this->container);
    }
}
