<?php

namespace Bibo\Mvc\Core\Logger;

use http\Exception\InvalidArgumentException;

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
     * Initialize the manager with a predefined set of loggers.
     *
     * @param array<string, Logger> $loggers Associative array keyed by logger name.
     */
    public function __construct(array $loggers)
    {
        $this->loggers = $loggers;
    }

    /**
     * Retrieve a logger by its registered name.
     *
     * @param string $name The identifier of the desired logger.
     *
     * @return Logger The logger instance associated with the given name.
     *
     * @throws InvalidArgumentException If the requested logger name is not registered.
     */
    public function get(string $name): Logger
    {
        if (!isset($this->loggers[$name])) {
            throw new InvalidArgumentException('Logger [' . $name . '] not defined.');
        }

        return $this->loggers[$name];
    }

    /**
     * Determine whether a logger with the given name exists.
     *
     * @param string $name The logger name to check.
     *
     * @return bool True if a logger is registered under the given name; otherwise false.
     */
    public function has(string $name): bool
    {
        return isset($this->loggers[$name]);
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
