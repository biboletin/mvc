<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\Cache;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;

/**
 * Class CacheServiceProvider
 * This class is responsible for registering the cache service in the container.
 * It creates an instance of the Cache class and binds it to the container.
 * It also provides a boot method to log the successful booting of the service.
 *
 * @package Bibo\Core\Providers
 */
class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {

        $this->container->set(Cache::class, fn () => new Cache());
    }

    /**
     * Boot the service provider
     */
    public function boot(): void
    {
    }
}
