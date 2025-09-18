<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Logger\Logger;
use Psr\Container\NotFoundExceptionInterface;

class FileCacheServiceProvider extends ServiceProvider
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
        $crypto = $this->container->get(Crypto::class);

        $fileCache = new FileCache(CACHE_PATH);
        $fileCache->setCrypto($crypto);
        $fileCache->setCachePrefix($config->get('cache.prefix'));
        $fileCache->setEncryption($config->get('cache.encryption'));
        $fileCache->setTtl($config->get('cache.ttl'));

        $this->container->set(FileCache::class, function () use ($fileCache) {
            return $fileCache;
        });
    }

    /**
     * Boot the service provider
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
