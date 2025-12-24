<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

/**
 * Service provider for configuring and registering middleware.
 *
 * It loads middleware configuration, registers global and route middleware,
 * defines middleware groups, and exposes a shared MiddlewareDispatcher.
 */
class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register a middleware dispatcher and configure middleware from bootstrap.
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(
            MiddlewareDispatcher::class,
            fn (ContainerInterface $container) => new MiddlewareDispatcher($container)
        );
    }

    /**
     * Boot the service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        $dispatcher = $this->container->get(MiddlewareDispatcher::class);

        $middlewares = require BOOTSTRAP_PATH . 'middleware.php';

        // Register global middleware
        $global = [];
        foreach ($middlewares['global'] ?? [] as $class) {
            $global[] = new $class($this->container);
        }
        $dispatcher->registerGlobal($global);

        // Register route middleware
        foreach ($middlewares['route'] ?? [] as $alias => $class) {
            $this->container->set($alias, fn () => new $class($this->container));

            $dispatcher->registerRouteMiddleware([
                $alias => $class,
            ]);
        }

        // Register middleware groups
        foreach ($middlewares['groups'] ?? [] as $name => $group) {
            $instances = array_map(fn ($class) => new $class($this->container), $group);
            $dispatcher->defineGroup($name, $instances);
        }
    }
}
