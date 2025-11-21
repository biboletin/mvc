<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\LogManager;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Psr\Container\ContainerExceptionInterface;
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
     */
    public function register(): void
    {
        $dispatcher = new MiddlewareDispatcher($this->container);

        $config = require BOOTSTRAP_PATH . 'middleware.php';

        // Register global middleware
        $global = [];
        foreach ($config['global'] ?? [] as $class) {
            $global[] = new $class($this->container);

            $this->container->set($class, fn () => new $class($this->container));
        }
        $dispatcher->registerGlobal($global);

        // Register route middleware
        foreach ($config['route'] ?? [] as $alias => $class) {
            $this->container->set($alias, fn () => new $class($this->container));

            $dispatcher->registerRouteMiddleware([
                $alias => $class,
            ]);
        }

        // Register middleware groups
        foreach ($config['groups'] ?? [] as $name => $group) {
            $instances = array_map(fn ($class) => new $class($this->container), $group);
            $dispatcher->defineGroup($name, $instances);
        }

        $this->container->set(MiddlewareDispatcher::class, function () use ($dispatcher) {
            return $dispatcher;
        });
    }

    /**
     * Boot service provider.
     *
     * @return void
     * @throws NotFoundExceptionInterface When the log manager service is not found.
     */
    public function boot(): void
    {
        try {
            $this->container
                ->get(LogManager::class)
                ->get('app')
                ->debug(__CLASS__ . ' booted successfully');
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
    }
}
