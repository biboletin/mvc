<?php

namespace Bibo\Core\Logger;

use Bibo\Core\Interfaces\LogHandlerInterface;

class SyslogHandler implements LogHandlerInterface
{
    public function write(string $level, string $message, array $context = []): void
    {
        $message = $this->interpolate($message, $context);
        syslog(LOG_INFO, "[$level] $message");
    }

    private function interpolate(string $message, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }
        return $message;
    }
}
