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
     * @param string $level   The severity level of the log message (e.g., 'info', 'error').
     * @param string $message The log message to be written.
     * @param array  $context Additional contextual data to be included with the log message.
     *
     * @return void
     */
    public function write(string $level, string $message, array $context = []): void;
}
