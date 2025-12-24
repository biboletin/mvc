<?php

namespace Bibo\Mvc\Core\Session;

/**
 * SessionManager
 *
 * Higher-level session API that sits on top of the low-level SessionHandler
 * wrapper and the EncryptedSessionHandler store. It provides:
 *  - metadata management (creation time, last access, IP, UA)
 *  - automatic metadata lifecycle when session starts / regenerates
 *  - helpers to atomically set values with metadata
 *  - runtime toggles to enable/disable encryption on the underlying store
 */
class SessionManager
{
    /**
     * The underlying session handler and store.
     *
     * @var SessionHandler
     */
    protected SessionHandler $session;

    /**
     * The underlying encrypted session store.
     *
     * @var EncryptedSessionHandler
     */
    protected EncryptedSessionHandler $store;

    /**
     * Reserved key used to store metadata within the session payload.
     */
    private const string META_KEY = '_meta';

    /**
     * Set the session handler and ensure meta structure exists.
     *
     * @param SessionHandler $session
     *
     * @return void
     */
    public function setSessionHandler(SessionHandler $session): void
    {
        $this->session = $session;

        // ensure meta structure exists
        if (!$this->session->has(self::META_KEY)) {
            $this->session->set(self::META_KEY, [
                'created_at' => time(),
                'last_active' => time(),
                'ip' => $this->getClientIp(),
                'ua' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            ]);
        }
    }

    /**
     * Convenience accessor for session handler.
     *
     * @return SessionHandler
     */
    public function getSessionHandler(): SessionHandler
    {
        return $this->session;
    }

    /**
     * Set the underlying encrypted session store.
     *
     * @param EncryptedSessionHandler $store
     *
     * @return void
     */
    public function setStore(EncryptedSessionHandler $store): void
    {
        $this->store = $store;
    }

    /**
     * Convenience accessor for encrypted session store.
     *
     * @return EncryptedSessionHandler
     */
    public function getStore(): EncryptedSessionHandler
    {
        return $this->store;
    }

    /**
     * Get session metadata key
     */
    public function getMeta(): array
    {
        return $this->session->get(self::META_KEY, []);
    }

    /**
     * Update last_active timestamp and persist
     */
    public function touch(): void
    {
        $meta = $this->getMeta();
        $meta['last_active'] = time();
        $this->session->set(self::META_KEY, $meta);
    }

    /**
     * Atomically set a value and update metadata
     */
    public function setWithMeta(string $key, mixed $value): void
    {
        $this->session->set($key, $value);
        $this->touch();
    }

    /**
     * Toggle encryption on the underlying storage handler. This only flips the
     * flag for future writes — existing session files remain as they are.
     */
    public function setEncryption(bool $enabled): void
    {
        $this->store->setEncrypted($enabled);
    }

    /**
     * Helper to regenerate ID and refresh metadata
     */
    public function regenerate(bool $deleteOld = true): void
    {
        $this->session->regenerate($deleteOld);

        $meta = $this->getMeta();
        $meta['regenerated_at'] = time();
        $this->session->set(self::META_KEY, $meta);
    }

    /**
     * Get client IP address
     *
     * @return string
     */
    private function getClientIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
}
