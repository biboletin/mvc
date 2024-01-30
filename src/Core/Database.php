<?php

namespace Biboletin\Mvc\Core;

/**
 *
 */
class Database
{
    /**
     * @var string
     */
    private string $host;

    /**
     * @var int
     */
    private int $port;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $database;

    /**
     * @var string
     */
    private string $encoding;

    /**
     * @var string
     */
    private string $collation;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }
}
