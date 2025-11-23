<?php

namespace Bibo\Mvc\Core\Database;

use Bibo\Mvc\Core\Database\Connection\DsnBuilder;
use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Drivers\MySqlDriver;
use Bibo\Mvc\Core\Database\Drivers\PgSqlDriver;
use Bibo\Mvc\Core\Database\Drivers\SqliteDriver;
use InvalidArgumentException;

class DriverFactory
{
    /**
     * Database configuration array.
     *
     * @var array
     */
    private array $config;

    /**
     * Data Source Name used for database connection.
     *
     * @var DsnBuilder|null
     */
    private ?DsnBuilder $dsn = null;

    /**
     * Constructor for the DriverFactory class.
     * Initializes the DSN and configuration properties.
     *
     * @param DsnBuilder|null $dsn The DSN used for database connection.
     * @param array $config The configuration array containing database connection details.
     */
    public function __construct(?DsnBuilder $dsn = null, array $config = [])
    {
        if ($dsn === null) {
            throw new InvalidArgumentException('Database DSN cannot be null.');
        }

        $this->dsn = $dsn;
        $this->config = $config;
    }

    /**
     * Creates and returns a database driver instance based on the configuration.
     *
     * @return DriverInterface The database driver instance corresponding to the specified driver in the configuration.
     * @throws InvalidArgumentException If the specified database driver is unsupported.
     */
    public function create(): DriverInterface
    {
        $driver = strtolower($this->config['driver']) ?? 'mysql';
        $dsn = $this->dsn->build($this->config);

        return match ($driver) {
            'pdo', 'mysql', 'mariadb' => new MySqlDriver($dsn, $this->config),
            'pgsql', 'postgres', 'postgresql' => new PgSqlDriver($dsn, $this->config),
            'sqlite' => new SqliteDriver($dsn, $this->config),
            default => throw new InvalidArgumentException('Unsupported database driver [' . $driver . ']'),
        };
    }

    /**
     * Destructor for the DriverFactory class.
     * Unsets the configuration and DSN properties to prevent memory leaks.
     */
    public function __destruct()
    {
        unset($this->config);
        $this->config = [];
        $this->dsn = null;
    }
}
