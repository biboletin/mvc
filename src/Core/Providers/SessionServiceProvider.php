<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Session\EncryptedSessionHandler;
use Bibo\Mvc\Core\Session\SessionHandler as SessionWrapper;
use Bibo\Mvc\Core\Session\SessionManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

/**
 * SessionServiceProvider (integrated)
 *
 * Bootstraps and registers the session subsystem into the DI container.
 * This provider constructs the encrypted file-backed session store, creates
 * the high-level session wrapper, and registers a SessionManager that
 * provides metadata helpers and runtime toggles.
 */
class SessionServiceProvider extends ServiceProvider
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
        // Register into container
        $this->container->set(EncryptedSessionHandler::class, fn () => new EncryptedSessionHandler());
        $this->container->set(SessionWrapper::class, fn () => new SessionWrapper());
        $this->container->set(SessionManager::class, fn () => new SessionManager());
    }

    /**
     * Boot the service provider
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        $config = $this->container->get(ConfigHandler::class);
        $crypto = $this->container->get(Crypto::class);

        // Build an encrypted storage handler and open a save path
        $store = $this->container->get(EncryptedSessionHandler::class);
        $store->setCrypto($crypto);
        $savePath = defined('SESSION_SAVE_PATH')
            ? SESSION_SAVE_PATH
            : ($config->get('session.save_path') ?? sys_get_temp_dir());
        $store->open($savePath, $config->get('session.name'));
        $store->setEncrypted($config->get('session.encrypt'));
        $store->setCompressed($config->get('session.compress'));

        // Prepare session options to pass to the wrapper
        $options = [
            'name' => $config->get('session.name'),
            'cookie_lifetime' => (int) $config->get('session.lifetime'),
            'cookie_path' => $config->get('session.path'),
            'cookie_domain' => $config->get('session.domain'),
            'cookie_secure' => (bool) $config->get('session.secure'),
            'cookie_httponly' => (bool) $config->get('session.httponly'),
            'cookie_samesite' => $config->get('session.samesite') ?: 'Lax',
            'save_path' => $savePath,
        ];

        // Create the wrapper which registers the handler and configures INI
        $session = $this->container->get(SessionWrapper::class);
        $session->setHandler($store);
        $session->setOptions($options);
        $session->start();

        // High-level manager (metadata + helpers)
        $manager = $this->container->get(SessionManager::class);
        $manager->setSessionHandler($session);
        $manager->setStore($store);
    }
}
