<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Config\Config;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws Exception
     */
    public function register(): void
    {
        // Create a cache instance if needed
        $cache = null;
        if (defined('CACHE_PATH')) {
            $cache = new FileCache(CACHE_PATH . 'config/');
        }

        // Create a config instance with cache
        $config = new Config($cache);

        // Load configuration
        $config->load();

/*
    // Load the environment file if it exists
        if (defined('ROOT_PATH') && file_exists(ROOT_PATH . '.env')) {
            $config->loadFromFile(ROOT_PATH . '.env');
        }
*/
        // Register the config instance in the container
        $this->container->set('config', function () use ($config) {
            return $config;
        });
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
