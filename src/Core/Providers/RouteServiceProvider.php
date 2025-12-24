<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Facades\Route;
use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Bibo\Mvc\Core\Resolver\ArgumentResolver;
use Bibo\Mvc\Core\Router\BaseRouter;
use Bibo\Mvc\Core\Router\MatchedRoute;
use Bibo\Mvc\Core\Router\RouteCollection;
use Bibo\Mvc\Core\Router\RouteDispatcher;
use Bibo\Mvc\Core\Router\RouteMatcher;
use Bibo\Mvc\Core\Strategies\Router\CachedRegexMatchStrategy;
use Bibo\Mvc\Core\Strategies\Router\CompositeMatchStrategy;
use Bibo\Mvc\Core\Strategies\Router\ExactMatchStrategy;
use Bibo\Mvc\Core\Strategies\Router\RegexMatchStrategy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
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
     *
     * @throws ContainerExceptionInterface If container interaction fails during setup.
     */
    public function register(): void
    {
        $this->container->set(RouteCollection::class, fn () => new RouteCollection());
        $this->container->set(
            ArgumentResolver::class,
            fn (ContainerInterface $container) => new ArgumentResolver($container)
        );

        $this->container->set(RouteMatchingStrategyInterface::class, function () {
            return new CompositeMatchStrategy([
                new ExactMatchStrategy(),
                new RegexMatchStrategy(),
                new CachedRegexMatchStrategy(),
            ]);
        });

        $this->container->set(RouteMatcher::class, function (ContainerInterface $container) {
            return new RouteMatcher(
                $container->get(RouteCollection::class),
                $container->get(RouteMatchingStrategyInterface::class)
            );
        });
// ->get(RouteMatcher::class)
        $this->container->set(RouteDispatcher::class, function (ContainerInterface $container) {
            return new RouteDispatcher(
                $container,
                $container->get(ArgumentResolver::class)
            );
        });
// $container->get(RouteMatchingStrategyInterface::class)
        $this->container->set(
            BaseRouter::class,
            fn (ContainerInterface $container) => new BaseRouter($container->get(RouteCollection::class))
        );
    }

    /**
     * Boot the service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface If no entry is found for the identifier.
     * @throws ContainerExceptionInterface If resolving the entry fails.
     * @throws ReflectionException
     */
    public function boot(): void
    {
        Route::init(
            $this->container->get(BaseRouter::class)
        );

        // Load application routes
        include ROUTES_PATH . 'web.php';
    }
}
