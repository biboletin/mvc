<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Logger\LogManager;
use Psr\Container\NotFoundExceptionInterface;

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
     */
    public function register(): void
    {
        $controller = new Controller($this->container);

        $this->container->set(Controller::class, function () use ($controller) {
            return $controller;
        });
    }

    /**
     * Boot the provider and log successful initialization.
     *
     * @throws NotFoundExceptionInterface When the log manager service is not found.
     * @return void
     */
    public function boot(): void
    {
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
