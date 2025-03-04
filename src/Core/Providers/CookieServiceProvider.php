<?php

namespace Bibo\Core\Provider;

use Bibo\Mvc\Core\Providers\ServiceProvider;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('cookies', fn () => null);
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
