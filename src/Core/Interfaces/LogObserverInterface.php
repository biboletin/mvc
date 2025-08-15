<?php

namespace Bibo\Mvc\Core\Interfaces;

use Stringable;

/**
 * Interface LogObserverInterface
 * This interface defines the contract for log observers.
 * Observers that implement this interface will be notified
 * when a log message is logged.
 * It provides a method to update the observer with the log level,
 * message, and optional context.
 */
interface LogObserverInterface
{
    /**
     * Update the observer with a log message.
     * This method will be called when a log message is logged.
     * The observer can implement its own logic to handle the log message.
     *
     * @param string            $level   The log level (e.g., 'info', 'error', 'debug').
     * @param string|Stringable $message The log message.
     * @param array             $context Optional context for the log message.
     */
    public function update(string $level, string|Stringable $message, array $context = []): void;
}
