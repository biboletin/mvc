<?php

namespace Bibo\Mvc\Core\Facades;

use Bibo\Mvc\Core\Enums\Environment;
use RuntimeException;

/**
 * Facade for accessing the current application environment.
 */
final class Env
{
    /**
     * The current environment instance.
     *
     * @var Environment|null
     */
    private static ?Environment $current = null;

    /**
     * Set the current environment.
     *
     * @param Environment $env The environment to set.
     */
    public static function set(Environment $env): void
    {
        self::$current = $env;
    }

    /**
     * Get the current environment.
     *
     * @return Environment The current environment.
     *
     * @throws RuntimeException If the environment has not been set.
     */
    public static function get(): Environment
    {
        if (self::$current === null) {
            throw new RuntimeException('Environment not set.');
        }

        return self::$current;
    }

    /**
     * Check if the current environment is development.
     *
     * @return bool True if the environment is development, false otherwise.
     */
    public static function isDevelopment(): bool
    {
        return self::get()->isDevelopment();
    }

    /**
     * Check if the current environment is production.
     *
     * @return bool True if the environment is production, false otherwise.
     */
    public static function isProduction(): bool
    {
        return self::get()->isProduction();
    }

    /**
     * Check if the current environment is testing.
     *
     * @return bool True if the environment is testing, false otherwise.
     */
    public static function isTesting(): bool
    {
        return self::get()->isTesting();
    }

    /**
     * Check if the current environment is staging.
     *
     * @return bool True if the environment is staging, false otherwise.
     */
    public static function isStaging(): bool
    {
        return self::get()->isStaging();
    }
}
