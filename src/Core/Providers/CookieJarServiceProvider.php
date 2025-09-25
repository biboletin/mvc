<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Cookie\CookieHandler;
use Bibo\Mvc\Core\Cookie\CookieJarHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Logger\LogManager;
use Psr\Container\NotFoundExceptionInterface;

class CookieJarServiceProvider extends ServiceProvider
{
    /**
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $config = $this->container->get(ConfigHandler::class);
        $crypto = new Crypto(
            $config->get('encryption.key'),
            $config->get('encryption.cipher'),
            $config->get('encryption.iv_length'),
            $config->get('encryption.use_hmac')
        );
        $cookie = $this->container->get(CookieHandler::class);
        $cookie->setName('session_id');
        $cookie->setValue('value');

        $cookieJar = new CookieJarHandler();
        $cookieJar->setCookie($cookie);
        $cookieJar->setCrypto($crypto);
        $cookieJar->setEncrypted($config->get('cookie.encrypted'));

        $this->container->set(CookieJarHandler::class, function () use ($cookieJar) {
            return $cookieJar;
        });
    }

    /**
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
