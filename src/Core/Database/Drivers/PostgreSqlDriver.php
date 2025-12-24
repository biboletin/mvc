<?php

namespace Bibo\Mvc\Core\Database\Drivers;

use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use PDO;
use PDOException;

/**
 * Class PostgreSqlDriver
 *
 * Implements a database driver for PostgreSQL using PDO.
 * Handles connection, disconnection, PDO attributes, and provides helper methods
 * for retrieving driver and database information.
 *
 * @package Bibo\Mvc\Core\Database\Drivers
 */
class PostgreSqlDriver implements DriverInterface, PdoDriverInterface
{
    /**
     * The PDO instance representing the PostgreSQL connection.
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
     * PostgreSQL-specific configuration options such as username, password, schema, timezone, and PDO options.
     *
     * @var array
     */
    private array $config;

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
     * @param string $dsn PostgreSQL DSN string
     * @param array $config Configuration array containing connection details
     */
    public function __construct(string $dsn, array $config)
    {
        $this->dsn = $dsn;
        $this->config = $config;
        $this->maxRetries = $config['max_retries'] ?? 3;
    }

    /**
     * Establishes a PDO connection to the PostgreSQL database.
     *
     * Handles retries with exponential backoff in case of connection failure.
     * Optionally sets search_path (schema) and timezone if provided in the configuration.
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

                // Set schema (search_path) if provided
                if (!empty($this->config['schema'])) {
                    $this->pdo->exec('SET search_path TO ' . $this->pdo->quote($this->config['schema']));
                }

                // Set timezone if provided
                if (!empty($this->config['timezone'])) {
                    $this->pdo->exec('SET TIMEZONE ' . $this->pdo->quote($this->config['timezone']));
                }

                return $this->pdo;
            } catch (PDOException $e) {
                $this->lastError = $e->getMessage();
                $this->errorCode = $e->getCode();
                $attempts++;
                // Exponential backoff (0.5s, 1s, 2s, ...)
                usleep((int)(pow(2, $attempts - 1) * 500_000));
            }
        }

        throw new PDOException(
            'Failed to connect to PostgreSQL after ' .
            $this->maxRetries . ' attempts: ' . $this->lastError,
            $this->errorCode
        );
    }

    /**
     * Prepare PDO options, merging provided options with PostgreSQL-safe defaults.
     *
     * Avoids MySQL-specific attributes and ensures safe defaults.
     *
     * @param string|array $options PDO options array or comma-separated string
     *
     * @return array
     */
    private function prepareOptions(string|array $options): array
    {
        if (is_string($options)) {
            $options = array_map('trim', preg_split('/\s*,\s*/', $options));
        }

        if (!is_array($options)) {
            $options = [];
        }

        $defaultOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
            PDO::ATTR_PERSISTENT         => false,
        ];

        return $options + $defaultOptions;
    }

    /**
     * Disconnects from the PostgreSQL database.
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
        return 'pgsql';
    }

    /**
     * Returns the PDO client version.
     *
     * @return string
     */
    public function getDriverVersion(): string
    {
        return $this->pdo
            ? $this->pdo->getAttribute(PDO::ATTR_CLIENT_VERSION)
            : 'unknown';
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
     * Returns the server version of PostgreSQL.
     *
     * @return string
     */
    public function getDatabaseVersion(): string
    {
        return $this->pdo
            ? $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION)
            : 'unknown';
    }

    /**
     * Returns detailed server information.
     *
     * @return string
     */
    public function getServerInfo(): string
    {
        return $this->pdo
            ? $this->pdo->getAttribute(PDO::ATTR_SERVER_INFO)
            : '';
    }

    /**
     * Returns the server version string (redundant with getDatabaseVersion).
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
        if (!$this->pdo) {
            return [];
        }

        return [
            PDO::ATTR_AUTOCOMMIT    => $this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT),
            PDO::ATTR_CLIENT_VERSION => $this->pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
            PDO::ATTR_DRIVER_NAME   => $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
        ];
    }

    /**
     * Sets a PDO attribute.
     *
     * @param int $attribute PDO::ATTR_* constant
     * @param mixed $value
     *
     * @return bool True on success
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
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError ?? '';
    }
}
