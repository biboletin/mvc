<?php

namespace Bibo\Mvc\Core\Logger;

use Bibo\Mvc\Core\Interfaces\LogObserverInterface;
use Psr\Log\AbstractLogger;
use Stringable;

/**
 * LogObserver class that implements the Observer pattern for logging.
 * This class allows multiple observers to be attached,
 * which will be notified when a log message is logged.
 * It extends AbstractLogger to provide a PSR-3 compliant logging interface.
 */
class LogObserver extends AbstractLogger
{
    /**
     * Array of attached observers.
     * This array holds the observers that will be notified when a log message is logged.
     * Each observer should implement the LogObserverInterface.
     * The observers are stored using their object hash as the key to ensure uniqueness.
     *
     * @var LogObserverInterface[]
     */
    private array $observers = [];

    /**
     * Attaches an observer to the log observer.
     * This method allows you to add an observer that will be notified
     * when a log message is logged.
     * The observer must implement the LogObserverInterface.
     *
     * @param LogObserverInterface $observer
     */
    public function attach(LogObserverInterface $observer): void
    {
        $this->observers[spl_object_hash($observer)] = $observer;
    }

    /**
     * Detaches an observer from the log observer.
     * This method allows you to remove an observer that was previously attached.
     * The observer must implement the LogObserverInterface.
     * If the observer is not found, it will simply do nothing.
     *
     * @param LogObserverInterface $observer
     */
    public function detach(LogObserverInterface $observer): void
    {
        unset($this->observers[spl_object_hash($observer)]);
    }

    /**
     * Notifies all attached observers with the log message.
     * This method is called to notify all observers when a log message is logged.
     * Each observer will receive the log level, message, and context.
     * Observers can implement their own logic to handle the log message.
     *
     * @param string            $level
     * @param string|Stringable $message
     * @param array             $context
     */
    public function notify(string $level, string|Stringable $message, array $context = []): void
    {
        foreach ($this->observers as $observer) {
            if ($observer instanceof LogObserverInterface) {
                $observer->update($level, $message, $context);
            }
        }
    }

    /**
     * Logs a message at the specified level.
     * This method is part of the PSR-3 logging interface.
     * It logs a message with the given level, message, and context.
     * The message will be passed to all attached observers.
     *
     * @param string            $level
     * @param string|Stringable $message
     * @param array             $context
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $this->notify($level, $message, $context);
    }
}
