<?php

namespace Bibo\Mvc\Core\Database\Drivers;

use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use PDO;
use PDOException;

/**
 * Class MySqlDriver
 *
 * Implements a database driver for MySQL (and MariaDB) using PDO.
 * Handles connection, disconnection, PDO attributes, and provides helper methods
 * for retrieving driver and database information.
 *
 * @package Bibo\Mvc\Core\Database\Drivers
 */
class MySqlDriver implements DriverInterface, PdoDriverInterface
{
    /**
     * The PDO instance representing the MySQL connection.
     *
     * @var PDO|null
     */
    private ?PDO $pdo = null;

    /**
     * The Data Source Name (DSN) string used for connecting via PDO.
     *
     * @var string
     */
    private string $dsn;

    /**
     * MySQL-specific configuration options such as username, password, charset, timezone, strict mode, and PDO options.
     *
     * @var array
     */
    private array $config;

    /**
     * Additional PDO attributes stored.
     *
     * @var array
     */
    private array $attributes = [];

    /**
     * The last error message encountered during connection attempts.
     *
     * @var string
     */
    private string $lastError = '';

    /**
     * The error code associated with the last error.
     *
     * @var int
     */
    private int $errorCode = 0;

    /**
     * The PDO error info string, if available.
     *
     * @var string
     */
    private string $errorInfo = '';

    /**
     * Maximum number of connection retry attempts before throwing an exception.
     *
     * @var int
     */
    private int $maxRetries = 3;

    /**
     * Constructor.
     *
     * Initializes the DSN, configuration, and maximum retry attempts.
     *
     * @param string $dsn DSN string for PDO
     * @param array $config Configuration array with database connection details
     */
    public function __construct(string $dsn, array $config)
    {
        $this->dsn = $dsn;
        $this->config = $config;
        $this->maxRetries = $config['max_retries'] ?? 3;
    }

    /**
     * Establishes a PDO connection to the MySQL database.
     *
     * Handles retries with exponential backoff in case of connection failure.
     * Optionally sets SQL strict mode and session timezone if provided in the configuration.
     *
     * @return PDO The connected PDO instance
     *
     * @throws PDOException if all retry attempts fail
     */
    public function connect(): PDO
    {
        if ($this->pdo) {
            return $this->pdo;
        }

        $attempts = 0;
        while ($attempts < $this->maxRetries) {
            try {
                $options = $this->prepareOptions($this->config['options'] ?? []);
                $this->pdo = new PDO(
                    $this->dsn,
                    $this->config['username'] ?? null,
                    $this->config['password'] ?? null,
                    $options
                );

                // Apply strict mode if configured
                if (!empty($this->config['strict'])) {
                    $this->pdo->exec("SET SESSION sql_mode='STRICT_ALL_TABLES'");
                }

                // Apply timezone if configured
                if (!empty($this->config['timezone'])) {
                    $this->pdo->exec('SET time_zone = ' . $this->pdo->quote($this->config['timezone']));
                }

                return $this->pdo;
            } catch (PDOException $e) {
                $this->lastError = $e->getMessage();
                $this->errorCode = $e->getCode();
                $attempts++;
                // Exponential backoff in seconds (0.5, 1, 2, ...)
                usleep((int)(pow(2, $attempts - 1) * 500_000));
            }
        }

        throw new PDOException(
            'Failed to connect to MySQL after ' . $this->maxRetries . ' attempts: ' . $this->lastError
        );
    }

    /**
     * Prepares PDO options by merging user-provided options with MySQL-safe defaults.
     *
     * @param string|array $options PDO options array or comma-separated string
     *
     * @return array Prepared PDO options
     */
    private function prepareOptions(string|array $options): array
    {
        $optionsToArray = is_string($options)
            ? array_map('trim', preg_split('/\s*,\s*/', $options, -1, PREG_SPLIT_NO_EMPTY))
            : $options;

        $defaultOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return $optionsToArray + $defaultOptions;
    }

    /**
     * Disconnects from the database.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->pdo = null;
    }

    /**
     * Returns the canonical driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'mysql';
    }

    /**
     * Returns the PDO driver name.
     *
     * @return string
     */
    public function getDriverVersion(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) : 'unknown';
    }

    /**
     * Returns the name of the connected database.
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->config['database'] ?? '';
    }

    /**
     * Returns the MySQL server version.
     *
     * @return string
     */
    public function getDatabaseVersion(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION) : 'unknown';
    }

    /**
     * Returns detailed information about the MySQL server.
     *
     * @return string
     */
    public function getServerInfo(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_SERVER_INFO) : '';
    }

    /**
     * Returns the MySQL server version (redundant with getDatabaseVersion).
     *
     * @return string
     */
    public function getServerVersion(): string
    {
        return $this->getDatabaseVersion();
    }

    /**
     * Returns the PDO instance for direct access.
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo ?? $this->connect();
    }

    /**
     * Retrieves a set of common PDO attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->pdo ? [
            PDO::ATTR_AUTOCOMMIT     => $this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT),
            PDO::ATTR_CASE           => $this->pdo->getAttribute(PDO::ATTR_CASE),
            PDO::ATTR_CLIENT_VERSION => $this->pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
        ] : [];
    }

    /**
     * Sets a PDO attribute.
     *
     * @param int $attribute PDO::ATTR_* constant
     * @param mixed $value
     *
     * @return bool True if the attribute was set successfully
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        return $this->getPdo()->setAttribute($attribute, $value);
    }

    /**
     * Retrieves a PDO attribute.
     *
     * @param int $attribute PDO::ATTR_* constant
     *
     * @return mixed
     */
    public function getAttribute(int $attribute): mixed
    {
        return $this->getPdo()->getAttribute($attribute);
    }

    /**
     * Returns the last error message from connection attempts.
     *
     * @return string Last error message or empty string if none
     */
    public function getLastError(): string
    {
        return $this->lastError ?? '';
    }
}
