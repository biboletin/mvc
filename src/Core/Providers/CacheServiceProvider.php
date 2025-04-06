<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Cache\Cache;
use Bibo\Mvc\Core\Providers\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $cache = new Cache();
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
