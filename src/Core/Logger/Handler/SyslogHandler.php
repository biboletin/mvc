<?php

namespace Bibo\Mvc\Core\Logger\Handler;

use Bibo\Mvc\Core\Interfaces\LogHandlerInterface;

/**
 * Class SyslogHandler
 * This class handles logging messages to the system log.
 * It implements the LogHandlerInterface.
 *
 * @package Bibo\Core\Logger
 */
class SyslogHandler implements LogHandlerInterface
{
    /**
     * SyslogHandler constructor.
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function write(string $level, string $message, array $context = []): void
    {
        $message = $this->interpolate($message, $context);
        syslog(LOG_INFO, '[' . strtoupper($level) . '] ' . $message);
    }

    /**
     * Interpolate the message with context
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    private function interpolate(string $message, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }
}
