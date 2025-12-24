<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Cache\NullCache;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Application\ConfigException;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class ConfigServiceProvider extends ServiceProvider
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
        // Register the config instance in the container
        $this->container->set(ConfigHandler::class, fn () => new ConfigHandler());
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        // Create a config instance
        $config = $this->container->get(ConfigHandler::class);
        $config->setConfigPath();

        // Load the environment file if it exists
        if (defined('ROOT_PATH') && file_exists(ROOT_PATH . '.env')) {
            try {
                $config->loadFromFile(ROOT_PATH . '.env');
            } catch (ConfigException $e) {
                echo 'Error loading .env file: ' . $e->getMessage();
            }
        }
        // Load configuration
        $config->load();

        // Setup file caching for config
        $cache = $config->get('cache.enabled')
            ? new FileCache(CACHE_PATH . 'config/')
            : new NullCache();

        $cache->setEnabled($config->get('cache.enabled'));
        $cache->setCachePrefix($config->get('cache.prefix'));
        // Setup encryption for config caching
        $crypto = new Crypto();

        $cache->setCrypto($crypto);
        $cache->setEncryption($config->get('cache.encryption'));
        $cache->setTtl($config->get('cache.ttl'));
        $cache->setUseCompression($config->get('cache.enable_compression'));
        $cache->setCompression($config->get('cache.compression'));
        $config->setFileCaching($cache);
    }
}
