<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class FileCacheServiceProvider extends ServiceProvider
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
        $this->container->set(FileCache::class, fn () => new FileCache(CACHE_PATH));
    }

    /**
     * Boot the service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        $config = $this->container->get(ConfigHandler::class);
        $crypto = $this->container->get(Crypto::class);

        $fileCache = $this->container->get(FileCache::class);
        $fileCache->setCrypto($crypto);
        $fileCache->setCachePrefix($config->get('cache.prefix'));
        $fileCache->setEncryption($config->get('cache.encryption'));
        $fileCache->setTtl($config->get('cache.ttl'));
    }
}
