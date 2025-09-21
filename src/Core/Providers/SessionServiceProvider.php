<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Crypto\Crypto;
use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\Session\EncryptedSessionHandler;
use Bibo\Mvc\Core\Session\SessionHandler;
use Psr\Container\NotFoundExceptionInterface;
use Random\RandomException;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface
     * @throws RandomException
     */
    public function register(): void
    {
        $config = $this->container->get(ConfigHandler::class);

        $session = new SessionHandler();
        $encryptedSessionHandler = new EncryptedSessionHandler($this->container->get(Crypto::class));
        $encryptedSessionHandler->setEncrypted($config->get('session.encrypt'));
        $encryptedSessionHandler->setCompressed($config->get('session.compress'));
        $session->setEncryption($this->container->get(Crypto::class));

        $session->setEncryptedSessionHandler($encryptedSessionHandler);

        $session->setEncrypted($config->get('session.encrypt'));
        $session->setName($config->get('session.name'));
        $session->setPrefix($config->get('session.prefix'));
        $session->setLifeTime($config->get('session.lifetime'));
        $session->setPath($config->get('session.path'));
        $session->setDomain($config->get('session.domain'));
        $session->setSecure($config->get('session.secure'));
        $session->setHttpOnly($config->get('session.httponly'));
        $session->setSameSite($config->get('session.samesite'));
        $session->setSavePath(SESSION_SAVE_PATH);
        $session->start();
        $session->set('user_id', 5);

        $this->container->set(SessionHandler::class, function () use ($session) {
            return $session;
        });
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
