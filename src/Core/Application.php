<?php

namespace Biboletin\Mvc\Core;

/**
 *
 */
abstract class Application
{
    /**
     * Application version
     *
     * @var string
     */
    private const VERSION = '0.0.0';
    /**
     * Application name
     *
     * @var string
     */
    private string $name = '';
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return void
     */
    public function init(): void
    {
    }

    /**
     * @return void
     */
    public function run(): void
    {
    }

    /**
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Application version
     *
     * @return string
     */
    public function version(): string
    {
        return self::VERSION;
    }
}
