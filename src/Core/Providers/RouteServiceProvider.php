<?php

namespace Bibo\Core\Provider;

use Bibo\Core\BaseRouter\BaseRouter;
use Bibo\Core\BaseRouter\CachedRegexMatchStrategy;
use Bibo\Core\Facades\Route;
use Bibo\Mvc\Core\Providers\ServiceProvider;

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

    public function boot(): void
    {
        include __DIR__ . '/../../../routes/web.php';

        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
