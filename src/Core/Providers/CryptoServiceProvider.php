<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Psr\Container\NotFoundExceptionInterface;

class CryptoServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider
     *
     * This method is called when the service provider is registered.
     * It can be used to bind services or perform any necessary setup.
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

        $this->container->set('crypto', function () use ($crypto) {
            return $crypto;
        });
    }

    /**
     * Boot the service provider
     *
     * This method is called after all service providers have been registered.
     * It can be used to perform any additional setup or configuration.
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
