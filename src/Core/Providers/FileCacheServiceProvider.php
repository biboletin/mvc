<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Cache\FileCache;
use Bibo\Mvc\Core\Providers\ServiceProvider;

class FileCacheServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $this->container->set('file_cache', fn () => new FileCache(CACHE_PATH));
    }

    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
