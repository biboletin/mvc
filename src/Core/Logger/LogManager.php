<?php

namespace Bibo\Mvc\Core\Logger;

use InvalidArgumentException;

/**
 * Manages a collection of named loggers and provides access to them.
 *
 * Typical usage:
 * - Inject this manager via the container.
 * - Retrieve a logger by name using get('app') or check existence with has('app').
 * - List all configured loggers with all().
 *
 * This class does not create loggers; it only stores and returns those provided to it.
 */
class LogManager
{
    /**
     * Map of logger name => Logger instance.
     *
     * @var array<string, Logger>
     */
    private array $loggers = [];

    /**
     * Register a logger with the manager.
     *
     * @param string $channel The logger name.
     * @param Logger $logger The logger instance.
     *
     * @return void
     */
    public function add(string $channel, Logger $logger): void
    {
        $this->loggers[$channel] = $logger;
    }

    /**
     * Retrieve a logger by its registered name.
     *
     * @param string $channel The identifier of the desired logger.
     *
     * @return Logger The logger instance associated with the given name.
     *
     * @throws InvalidArgumentException If the requested logger name is not registered.
     */
    public function get(string $channel): Logger
    {
        if (!isset($this->loggers[$channel])) {
            throw new InvalidArgumentException('Logger [' . $channel . '] not defined.');
        }

        return $this->loggers[$channel];
    }

    /**
     * Determine whether a logger with the given name exists.
     *
     * @param string $channel The logger name to check.
     *
     * @return bool True if a logger is registered under the given name; otherwise false.
     */
    public function has(string $channel): bool
    {
        return isset($this->loggers[$channel]);
    }

    /**
     * Get all registered loggers.
     *
     * @return array<string, Logger> Associative array of all loggers keyed by name.
     */
    public function all(): array
    {
        return $this->loggers;
    }
}
