<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Facades\Route;
use Bibo\Mvc\Core\Logger\Logger;
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

        $this->container->set(BaseRouter::class, fn () => $router);
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
