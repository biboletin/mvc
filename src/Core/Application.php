<?php

namespace Biboletin\Mvc\Core;

/**
 * Application class
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
     * Application constructor
     */
    public function __construct()
    {
    }

    /**
     * Initialize application
     *
     * @return void
     */
    public function init(): void
    {
    }

    /**
     * Run application
     *
     * @return void
     */
    public function run(): void
    {
    }

    /**
     * Register application
     *
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
