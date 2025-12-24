<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Cookie\CookieHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class CookieServiceProvider extends ServiceProvider
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
        $this->container->set(CookieHandler::class, fn () => new CookieHandler());
    }

    /**
     * Boot service provider
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

        $crypto = new Crypto();
        $cookie = $this->container->get(CookieHandler::class);
        $cookie->setCrypto($crypto);
        $cookie->setName($config->get('cookie.name'));
        $cookie->setPrefix($config->get('cookie.prefix'));
        $cookie->setExpire($config->get('cookie.expire'));
        $cookie->setPath($config->get('cookie.path'));
        $cookie->setDomain($config->get('cookie.domain'));
        $cookie->setSecure($config->get('cookie.secure'));
        $cookie->setHttpOnly($config->get('cookie.httponly'));
        $cookie->setSameSite($config->get('cookie.samesite'));
        $cookie->setEncrypted($config->get('cookie.encrypted'));
        $cookie->setPartitioned($config->get('cookie.partitioned'));
    }
}
