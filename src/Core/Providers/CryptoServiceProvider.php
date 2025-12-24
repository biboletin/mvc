<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

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
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(Crypto::class, fn () => new Crypto());
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
        $config = $this->container->get(ConfigHandler::class);
        $crypto = $this->container->get(Crypto::class);
        $crypto->setKey($config->get('encryption.key'));
        $crypto->setCipherAlgorithm($config->get('encryption.cipher'));
        $crypto->setIvLength($config->get('encryption.iv_length'));
        $crypto->setUseHmac($config->get('encryption.use_hmac'));
    }
}
