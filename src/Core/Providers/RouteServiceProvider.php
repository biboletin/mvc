<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Facades\Route;
use Bibo\Mvc\Core\Router\BaseRouter;
use Bibo\Mvc\Core\Router\CachedRegexMatchStrategy;
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
        $router = new BaseRouter($this->container, new CachedRegexMatchStrategy());
        Route::init($router);

        include ROUTES_PATH . 'web.php';

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
