<?php

namespace Bibo\Mvc\Core\Session;

use RuntimeException;
use SessionHandlerInterface;

/**
 * Class SessionHandler
 *
 * High-level session lifecycle manager that wraps a custom
 * SessionHandlerInterface implementation (e.g., EncryptedSessionHandler).
 *
 * Responsibilities:
 * - Configure session INI settings
 * - Register the underlying session save handler
 * - Handle start, save, regenerate, and flush operations
 * - Provide predictable exceptions on failure
 */
class SessionHandler
{
    /**
     * The custom session save handler implementation.
     *
     * @var SessionHandlerInterface
     */
    protected SessionHandlerInterface $handler;

    /**
     * Indicates whether the session has been started.
     *
     * @var bool Indicates whether the session has been started
     */
    protected bool $started = false;

    /**
     * Constructor.
     * Sets up the session handler with the provided options.
     * Throws RuntimeException on failure.
     *
     * @param SessionHandlerInterface $handler The custom session save handler implementation.
     * @param array $options Optional configuration for session INI directives.
     */
    public function __construct(SessionHandlerInterface $handler, array $options = [])
    {
        $this->handler = $handler;

        $this->configure($options);
        $this->registerHandler();
    }

    /**
     * Applies INI-level configuration for PHP's session behavior.
     *
     * @param array $options Key-value pairs of INI options.
     *
     * @return void
     */
    protected function configure(array $options): void
    {
        foreach ($options as $key => $value) {
            ini_set('session.' . $key, (string)$value);
        }
    }

    /**
     * Registers the custom session handler with PHP.
     * Throws RuntimeException on failure.
     *
     * @return void
     */
    protected function registerHandler(): void
    {
        if (!session_set_save_handler($this->handler, true)) {
            throw new RuntimeException('Failed to register the custom session handler');
        }
    }

    /**
     * Starts the session if not already started.
     *
     * @return void
     * @throws RuntimeException If session_start() fails.
     */
    public function start(): void
    {
        if ($this->started) {
            return;
        }

        if (!session_start()) {
            throw new RuntimeException('session_start() failed — check session save handler or permissions');
        }

        $this->started = true;
    }

    /**
     * Saves session data and closes the session.
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->started) {
            session_write_close();
            $this->started = false;
        }
    }

    /**
     * Regenerates the session ID.
     * Useful to prevent session fixation.
     *
     * @param bool $deleteOld Whether to delete the old session file.
     *
     * @return void
     * @throws RuntimeException If regeneration fails.
     */
    public function regenerate(bool $deleteOld = true): void
    {
        if (!session_regenerate_id($deleteOld)) {
            throw new RuntimeException('session_regenerate_id() failed');
        }
    }

    /**
     * Explicitly destroys the current session.
     *
     * @return void
     */
    public function destroy(): void
    {
        if ($this->started) {
            session_unset();
            session_destroy();
            $this->started = false;
        }
    }

    /**
     * Convenience accessor for session values.
     *
     * @param string $key The session key to retrieve.
     * @param mixed|null $default Default value if key does not exist.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Sets a value in the session.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Removes a key from the session.
     *
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Checks if a session key exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }
}
