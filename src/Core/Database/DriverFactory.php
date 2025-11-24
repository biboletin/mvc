<?php

namespace Bibo\Mvc\Core\Database;

use Bibo\Mvc\Core\Database\Connection\DsnBuilder;
use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Drivers\MySqlDriver;
use Bibo\Mvc\Core\Database\Drivers\PostgreSqlDriver;
use Bibo\Mvc\Core\Database\Drivers\SqliteDriver;
use InvalidArgumentException;

/**
 * Class DriverFactory
 *
 * Factory responsible for creating database driver instances
 * based on the provided configuration and driver type.
 *
 * - Normalizes driver aliases (postgres, pgsql, sqlite3, mariadb, etc.)
 * - Builds the DSN using DsnBuilder
 * - Returns the correct driver class instance
 *
 * @package Bibo\Mvc\Core\Database
 */
class DriverFactory
{
    /**
     * The configuration array for the database connection.
     *
     * @var array
     */
    private array $config;

    /**
     * The DSN builder instance used to generate a full DSN string.
     *
     * @var DsnBuilder|null
     */
    private ?DsnBuilder $dsn = null;

    /**
     * Maps driver aliases to their canonical internal driver names.
     *
     * @var array<string,string>
     */
    private static array $aliases = [
        'mysql' => 'mysql',
        'mariadb' => 'mysql',

        'sqlite' => 'sqlite',
        'sqlite3' => 'sqlite',

        'pgsql' => 'pgsql',
        'postgres' => 'pgsql',
        'postgresql' => 'pgsql',

        'redis' => 'redis', // For future support
    ];

    /**
     * DriverFactory constructor.
     *
     * @param DsnBuilder|null $dsn    DSN builder used to generate valid DSN strings.
     * @param array           $config Database configuration array.
     *
     * @throws InvalidArgumentException When DSN is not provided.
     */
    public function __construct(?DsnBuilder $dsn = null, array $config = [])
    {
        if ($dsn === null) {
            throw new InvalidArgumentException('DsnBuilder instance cannot be null.');
        }

        $this->dsn = $dsn;
        $this->config = $config;
    }

    /**
     * Creates the correct database driver instance based on configuration.
     *
     * @return DriverInterface
     *
     * @throws InvalidArgumentException When driver is missing, unsupported, or DSN build fails.
     */
    public function create(): DriverInterface
    {
        if (!isset($this->config['driver'])) {
            throw new InvalidArgumentException('Database driver is not defined in configuration.');
        }

        $driver = strtolower($this->resolve($this->config['driver']));
        $dsn = $this->dsn->build($this->config);

        return match ($driver) {
            'mysql'  => new MySqlDriver($dsn, $this->config),
            'pgsql'  => new PostgreSqlDriver($dsn, $this->config),
            'sqlite' => new SqliteDriver($dsn, $this->config),

            default => throw new InvalidArgumentException('Unsupported database driver [' . $driver . '}]'),
        };
    }

    /**
     * Resolves a driver alias to its canonical driver name.
     *
     * Examples:
     * - "postgres" → "pgsql"
     * - "mariadb"  → "mysql"
     * - "sqlite3"  → "sqlite"
     *
     * @param string $driver
     *
     * @return string Canonical driver name.
     *
     * @throws InvalidArgumentException When alias cannot be resolved.
     */
    public static function resolve(string $driver): string
    {
        return self::$aliases[strtolower($driver)]
            ?? throw new InvalidArgumentException('Unknown driver alias: {' . $driver . '}');
    }

    /**
     * Destructor - clears sensitive configuration data.
     *
     * Helps avoid keeping database credentials in memory.
     */
    public function __destruct()
    {
        $this->config = [];
        $this->dsn = null;
    }
}
