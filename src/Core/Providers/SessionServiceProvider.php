<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Logger\LogManager;
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
    public function register(): void
    {
        try {
            $config = $this->container->get(ConfigHandler::class);
            $crypto = $this->container->get(Crypto::class);

            // Build encrypted storage handler and open save path
            $store = new EncryptedSessionHandler($crypto);
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
            $session = new SessionWrapper($store, $options);
            $session->start();

            // High-level manager (metadata + helpers)
            $manager = new SessionManager($session, $store);

            // Register into container
            $this->container->set(SessionWrapper::class, function () use ($session) {
                return $session;
            });

            $this->container->set(SessionManager::class, function () use ($manager) {
                return $manager;
            });
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
            // swallow to avoid fatal bootstrap errors; provider can be retried later
        }
    }

    public function boot(): void
    {
        try {
            $this->container
                ->get(LogManager::class)
                ->get('app')
                ->debug(__CLASS__ . ' booted successfully');
        } catch (NotFoundExceptionInterface | ReflectionException | ContainerExceptionInterface $e) {
        }
    }
}
