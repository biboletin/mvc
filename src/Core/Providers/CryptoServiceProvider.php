<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Crypto\DecryptException;
use Bibo\Mvc\Core\Logger\LogManager;
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
     * @throws NotFoundExceptionInterface
     * @throws DecryptException
     */
    public function register(): void
    {
        try {
            $config = $this->container->get(ConfigHandler::class);
            $crypto = new Crypto(
                $config->get('encryption.key'),
                $config->get('encryption.cipher'),
                $config->get('encryption.iv_length'),
                $config->get('encryption.use_hmac')
            );

            $this->container->set(Crypto::class, function () use ($crypto) {
                return $crypto;
            });
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
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
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
