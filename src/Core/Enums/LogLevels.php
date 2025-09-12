<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

enum LogLevels: string
{
    case Emergency = 'emergency';

    case Alert = 'alert';

    case Critical = 'critical';

    case Error = 'error';

    case Warning = 'warning';

    case Notice = 'notice';

    case Info = 'info';

    case Debug = 'debug';

    case None = 'none';

    /**
     * Check if the log level is emergency
     *
     * @return bool
     */

    public function isEmergency(): bool
    {
        return $this === self::Emergency;
    }

    /**
     * Check if the log level is alert
     *
     * @return bool
     */

    public function isAlert(): bool
    {
        return $this === self::Alert;
    }

    /**
     * Check if the log level is critical
     *
     * @return bool
     */

    public function isCritical(): bool
    {
        return $this === self::Critical;
    }

    /**
     * Check if the log level is error
     *
     * @return bool
     */

    public function isError(): bool
    {
        return $this === self::Error;
    }

    /**
     * Check if the log level is warning
     *
     * @return bool
     */

    public function isWarning(): bool
    {
        return $this === self::Warning;
    }

    /**
     * Check if the log level is notice
     *
     * @return bool
     */

    public function isNotice(): bool
    {
        return $this === self::Notice;
    }

    /**
     * Check if the log level is info
     *
     * @return bool
     */

    public function isInfo(): bool
    {
        return $this === self::Info;
    }

    /**
     * Check if the log level is debug
     *
     * @return bool
     */

    public function isDebug(): bool
    {
        return $this === self::Debug;
    }

    /**
     * Check if the log level is none
     *
     * @return bool
     */

    public function isNone(): bool
    {
        return $this === self::None;
    }

    /**
     * Get the log level as a string
     *
     * @return string
     */

    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Get the log level from a string
     *
     * @param string $value
     *
     * @return self
     */

    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'emergency' => self::Emergency,
            'alert' => self::Alert,
            'critical' => self::Critical,
            'error' => self::Error,
            'warning' => self::Warning,
            'notice' => self::Notice,
            'info' => self::Info,
            'debug' => self::Debug,
            'none' => self::None,
            default => throw new InvalidArgumentException('Invalid log level: ' . $value),
        };
    }

    /**
     * Get all log levels as an array
     *
     * @return array
     */

    public static function all(): array
    {
        return array_map(fn($level) => $level->value, self::cases());
    }

    /**
     * Check if the log level is valid
     *
     * @param string $value
     *
     * @return bool
     * @throws InvalidArgumentException if the log level is not valid
     */

    public static function isValid(string $value): bool
    {
        try {
            self::fromString($value);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
