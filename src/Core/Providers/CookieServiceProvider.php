<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Cookie\CookieHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Bibo\Mvc\Core\Exception\Custom\Crypto\EncryptException;
use Psr\Container\NotFoundExceptionInterface;
use Random\RandomException;

class CookieServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface
     * @throws DecryptException
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
        $cookie = new CookieHandler();
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

        $this->container->set('cookie', function () use ($cookie) {
            return $cookie;
        });
    }

    /**
     * Boot service
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
