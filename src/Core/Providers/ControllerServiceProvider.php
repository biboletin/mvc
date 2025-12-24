<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerInterface;

/**
 * Service provider responsible for registering the base Controller instance
 * in the service container and performing provider bootstrapping.
 */
class ControllerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings/services into the container.
     *
     * Creates a shared Controller instance bound by its class name so that
     * controllers depending on the base Controller can resolve it easily.
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(Controller::class, fn (ContainerInterface $container) => new Controller($container));
    }

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
    }
}
