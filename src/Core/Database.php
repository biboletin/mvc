<?php

namespace Biboletin\Mvc\Core;

/**
 * Database class
 */
class Database
{
    /**
     * Database engine
     *
     * @var string
     */
    private string $engine = '';
    /**
     * Database host
     *
     * @var string
     */
    private string $host;

    /**
     * Database port
     *
     * @var int
     */
    private int $port;

    /**
     * Database username
     *
     * @var string
     */
    private string $username;

    /**
     * Database password
     *
     * @var string
     */
    private string $password;

    /**
     * Database name
     *
     * @var string
     */
    private string $database;

    /**
     * Database charset
     *
     * @var string
     */
    private string $encoding;

    /**
     * Database collation
     *
     * @var string
     */
    private string $collation;

    /**
     * Database constructor
     */
    public function __construct()
    {
    }

    /**
     * Database destructor
     */
    public function __destruct()
    {
    }
}
