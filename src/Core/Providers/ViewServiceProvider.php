<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\View\View;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ViewServiceProvider
 * This class is responsible for registering the view service in the container.
 * It creates an instance of the View class and binds it to the container.
 * It also provides a boot method to log the successful booting of the service.
 *
 * @package Bibo\Core\Providers
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function register(): void
    {
        $this->container->set(View::class, fn (ContainerInterface $container) => new View($container));
    }

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
    }
}
