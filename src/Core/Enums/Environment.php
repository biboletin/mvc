<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

enum Environment: string
{
    case DEVELOPMENT = 'development';

    case PRODUCTION = 'production';

    case TESTING = 'testing';

    case STAGING = 'staging';

    /**
     * Check if the environment is development
     *
     * @return bool
     */

    public function isDevelopment(): bool
    {
        return $this === self::DEVELOPMENT;
    }

    /**
     * Check if the environment is production
     *
     * @return bool
     */

    public function isProduction(): bool
    {
        return $this === self::PRODUCTION;
    }

    /**
     * Check if the environment is testing
     *
     * @return bool
     */

    public function isTesting(): bool
    {
        return $this === self::TESTING;
    }

    /**
     * Check if the environment is staging
     *
     * @return bool
     */

    public function isStaging(): bool
    {
        return $this === self::STAGING;
    }

    /**
     * Get the environment from a string
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     *
     * @return self
     */

    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'development' => self::DEVELOPMENT,
            'production' => self::PRODUCTION,
            'testing' => self::TESTING,
            'staging' => self::STAGING,
            default => throw new InvalidArgumentException('Invalid environment: ' . $value),
        };
    }

    /**
     * Get all environments as an array
     *
     * @return array
     */

    public static function all(): array
    {
        return array_map(fn($env) => $env->value, self::cases());
    }

    /**
     * Check if the environment is valid
     *
     * @param string $value
     *
     * @return bool
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
