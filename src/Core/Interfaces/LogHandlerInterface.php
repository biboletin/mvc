<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Provides a contract for logging handlers to implement custom log writing functionality.
 *
 * Methods in this interface allow the handling of log messages with specified severity levels,
 * contextual data, and message details.
 */
interface LogHandlerInterface
{
    /**
     * Writes a log message with the specified severity level and context.
     *
     * @param string $message The log message to be written.
     *
     * @return void
     */
    public function write(string $message): void;
}
