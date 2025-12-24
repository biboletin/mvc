<?php

namespace Bibo\Mvc\Core\Database\Drivers;

use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use PDO;
use PDOException;

/**
 * Class SqliteDriver
 *
 * Implements DriverInterface and PdoDriverInterface for SQLite database connections.
 * Handles PDO connection setup, configuration, error handling, and retrieval of SQLite-specific information.
 *
 * SQLite notes:
 * - Foreign keys are disabled by default and explicitly enabled in `connect()`.
 * - PDO emulates prepared statements by default for SQLite; disabled here for consistency.
 * - SQLite uses file-based databases, so DSN usually looks like "sqlite:/path/to/db.sqlite".
 */
class SqliteDriver implements DriverInterface, PdoDriverInterface
{
    /**
     * The PDO instance representing the SQLite connection.
     *
     * @var PDO|null
     */
    private ?PDO $pdo = null;

    /**
     * The DSN string used to connect to SQLite via PDO.
     *
     * @var string
     */
    private string $dsn;

    /**
     * SQLite-specific configuration array (e.g., database name, options, max retries).
     *
     * @var array
     */
    private array $config;

    /**
     * Last error message encountered during connection attempts.
     *
     * @var string
     */
    private string $lastError = '';

    /**
     * Maximum number of connection attempts before giving up.
     *
     * @var int
     */
    private int $maxRetries = 3;

    /**
     * Constructor.
     *
     * @param string $dsn The DSN string for SQLite (e.g., "sqlite:/path/to/db.sqlite")
     * @param array $config Optional SQLite configuration (options, max_retries, database)
     */
    public function __construct(string $dsn, array $config = [])
    {
        $this->dsn = $dsn;
        $this->config = $config;
        $this->maxRetries = $config['max_retries'] ?? 3;
    }

    /**
     * Connects to the SQLite database using PDO.
     *
     * - Enables foreign keys.
     * - Retries up to $maxRetries times in case of failure.
     * - Throws PDOException if connection fails.
     *
     * @return PDO The PDO instance representing the SQLite connection.
     *
     * @throws PDOException
     */
    public function connect(): PDO
    {
        if ($this->pdo) {
            return $this->pdo;
        }

        $attempts = 0;

        while ($attempts < $this->maxRetries) {
            try {
                unset($this->config['options'][PDO::MYSQL_ATTR_INIT_COMMAND]); // Remove MySQL-specific option

                $options = $this->prepareOptions($this->config['options'] ?? []);
                $this->pdo = new PDO($this->dsn, null, null, $options);

                // Enable foreign keys explicitly
                $this->pdo->exec('PRAGMA foreign_keys = ON');

                return $this->pdo;
            } catch (PDOException $e) {
                $this->lastError = $e->getMessage();
                $attempts++;
                usleep($attempts * 150_000); // small exponential backoff
            }
        }

        throw new PDOException(
            'Failed to connect to SQLite after ' . $this->maxRetries . ' attempts: ' . $this->lastError
        );
    }

    /**
     * Prepare PDO options array, merging defaults with user-supplied options.
     *
     * @param array|string $options
     *
     * @return array
     */
    private function prepareOptions(array|string $options): array
    {
        $optionsArray = is_string($options)
            ? array_map('trim', preg_split('/\s*,\s*/', $options, -1, PREG_SPLIT_NO_EMPTY))
            : $options;

        $defaults = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return $optionsArray + $defaults;
    }

    /**
     * Disconnects from SQLite by destroying the PDO instance.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->pdo = null;
    }

    /**
     * Returns the SQLite driver name.
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'sqlite';
    }

    /**
     * Returns the SQLite driver version.
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
     * Returns the name of the connected SQLite database file.
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->config['database'] ?? '';
    }

    /**
     * Returns the SQLite database version string.
     *
     * @return string
     */
    public function getDatabaseVersion(): string
    {
        if ($this->pdo) {
            return $this->pdo->query('SELECT sqlite_version()')->fetchColumn();
        }
        return 'unknown';
    }

    /**
     * Returns server info, here the SQLite engine version.
     *
     * @return string
     */
    public function getServerInfo(): string
    {
        return $this->getDatabaseVersion();
    }

    /**
     * Returns the SQLite server version.
     *
     * @return string
     */
    public function getServerVersion(): string
    {
        return $this->getDatabaseVersion();
    }

    /**
     * Returns the PDO instance.
     *
     * @return PDO
     */
    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            $this->connect();
        }
        return $this->pdo;
    }

    /**
     * Returns a list of selected PDO attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        if (!$this->pdo) {
            return [];
        }

        return [
            PDO::ATTR_ERRMODE => $this->pdo->getAttribute(PDO::ATTR_ERRMODE),
            PDO::ATTR_DEFAULT_FETCH_MODE => $this->pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE),
        ];
    }

    /**
     * Sets a PDO attribute.
     *
     * @param int $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        return $this->getPdo()->setAttribute($attribute, $value);
    }

    /**
     * Retrieves a PDO attribute.
     *
     * @param int $attribute
     *
     * @return mixed
     */
    public function getAttribute(int $attribute): mixed
    {
        return $this->getPdo()->getAttribute($attribute);
    }

    /**
     * Returns the last connection error message.
     *
     * @return string
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }
}
