<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Container\Container;
use Bibo\Mvc\Core\Interfaces\ServiceProviderInterface;

/**
 * Service provider class
 */
abstract class ServiceProvider implements ServiceProviderInterface
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

    /**
     * Boot service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
