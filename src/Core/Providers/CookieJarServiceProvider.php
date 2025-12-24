<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Cookie\CookieHandler;
use Bibo\Mvc\Core\Cookie\CookieJarHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class CookieJarServiceProvider extends ServiceProvider
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
        $this->container->set(CookieJarHandler::class, fn () => new CookieJarHandler());
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
        $crypto = new Crypto();
        $cookie = $this->container->get(CookieHandler::class);
        $cookie->setName('session_id');
        $cookie->setValue('value');

        $cookieJar = $this->container->get(CookieJarHandler::class);
        $cookieJar->setCookie($cookie);
        $cookieJar->setCrypto($crypto);
        $cookieJar->setEncrypted($config->get('cookie.encrypted'));
    }
}
