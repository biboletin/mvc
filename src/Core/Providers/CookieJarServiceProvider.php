<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cookie\CookieJarHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Psr\Container\NotFoundExceptionInterface;
use Random\RandomException;

class CookieJarServiceProvider extends ServiceProvider
{
    /**
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        $config = $this->container->get('config');
        $crypto = new Crypto(
            $config->get('encryption.key'),
            $config->get('encryption.cipher'),
            $config->get('encryption.iv_length'),
            $config->get('encryption.use_hmac')
        );
        $cookie = $this->container->get('cookie');
        $cookie->setName('session_id');
        $cookie->setValue('value');

        $cookieJar = new CookieJarHandler();
        $cookieJar->setCookie($cookie);
        $cookieJar->setCrypto($crypto);
        $cookieJar->setEncrypted($config->get('cookie.encrypted'));

        $this->container->set('cookie_jar', function () use ($cookieJar) {
            return $cookieJar;
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
