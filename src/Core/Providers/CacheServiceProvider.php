<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\Cache;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Logger\LogManager;
use Psr\Container\NotFoundExceptionInterface;

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
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $config = $this->container->get(ConfigHandler::class);
        $cache = new Cache();
    }

    /**
     * Boot the service provider
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
