<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Psr\Container\NotFoundExceptionInterface;

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

    /**
     * Boot the service provider
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
