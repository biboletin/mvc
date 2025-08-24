<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Router\BaseRouter;
use Bibo\Mvc\Core\Router\CachedRegexMatchStrategy;
use Bibo\Mvc\Core\Facades\Route;
use Psr\Container\NotFoundExceptionInterface;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $container = $this->container;
        $router = new BaseRouter($container, new CachedRegexMatchStrategy());
        Route::init($router);

        include __DIR__ . '/../../../routes/web.php';

        $this->container->set('router', fn () => $router);
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
