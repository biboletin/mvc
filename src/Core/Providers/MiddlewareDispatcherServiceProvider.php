<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Psr\Container\NotFoundExceptionInterface;

class MiddlewareDispatcherServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $dispatcher = new MiddlewareDispatcher($this->container);

        $config = require BOOTSTRAP_PATH . 'middleware.php';


        // Register route middleware
        foreach ($config['route'] ?? [] as $alias => $class) {
            $this->container->set($alias, fn () => new $class($this->container));

            $dispatcher->registerRouteMiddleware([
                $alias => $class,
            ]);
        }

        // Register global middleware
        foreach ($config['global'] ?? [] as $class) {
            $dispatcher->registerGlobal([
                new $class($this->container),
            ]);
        }

        // Register middleware groups
        foreach ($config['groups'] ?? [] as $name => $group) {
            $instances = array_map(fn($class) => new $class($this->container), $group);
            $dispatcher->defineGroup($name, $instances);
        }

        $this->container->set('middleware_dispatcher', function () use ($dispatcher) {
            return $dispatcher;
        });
    }

    /**
     * Boot service provider
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
