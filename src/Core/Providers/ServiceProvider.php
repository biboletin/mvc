<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Core\Container\Container;

/**
 * Service provider class
 */
abstract class ServiceProvider
{
    /**
     * Container
     *
     * @var Container
     */
    protected Container $container;

    /**
     * Constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Register service provider
     *
     * @return void
     */
    abstract public function register(): void;

    public function boot(): void
    {
    }
}
