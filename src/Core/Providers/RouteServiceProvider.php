<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Facades\Route;
use Bibo\Mvc\Core\Logger\LogManager;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Bibo\Mvc\Core\Router\BaseRouter;
use Bibo\Mvc\Core\Strategies\Router\CachedRegexMatchStrategy;
use Bibo\Mvc\Core\Strategies\Router\CompositeMatchStrategy;
use Bibo\Mvc\Core\Strategies\Router\ExactMatchStrategy;
use Bibo\Mvc\Core\Strategies\Router\RegexMatchStrategy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

/**
 * Service provider that bootstraps the routing layer.
 *
 * It composes route matching strategies, initializes the router and facade,
 * loads route definitions, and registers the router instance in the container.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register router and strategies, then load route definitions.
     *
     * @return void
     * @throws ContainerExceptionInterface If container interaction fails during setup.
     * @throws ReflectionException
     */
    public function register(): void
    {
        $routeStrategies = [
            new ExactMatchStrategy(),
            new RegexMatchStrategy(),
            new CachedRegexMatchStrategy(),
        ];
        $strategy = new CompositeMatchStrategy($routeStrategies);
        $router = new BaseRouter();
        $router
            ->setContainer($this->container)
            ->setMiddlewareDispatcher($this->container->get(MiddlewareDispatcher::class))
            ->setStrategy($strategy);

        Route::init($router);

        // Load application routes
        include ROUTES_PATH . 'web.php';

        // Expose the router via the container
        $this->container->set(BaseRouter::class, fn () => $router);
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
