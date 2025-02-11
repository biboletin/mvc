<?php

namespace Bibo\Core\Base;

use Psr\Container\ContainerInterface;

/**
 * App class
 */
class App
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Container
     *
     * @return ContainerInterface
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Run app
     *
     * @return void
     */
    public function run(): void
    {
    }
}
