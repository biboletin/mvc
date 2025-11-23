<?php

namespace Bibo\Mvc\Core\Database\Drivers;

use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use PDO;
use PDOException;

class MySqlDriver implements DriverInterface, PdoDriverInterface
{
    private ?PDO $pdo = null;

    private string $dsn;

    private array $config;

    private array $attributes = [];

    private string $lastError = '';

    private int $errorCode = 0;

    private string $errorInfo = '';

    private int $maxRetries = 3;

    public function __construct(string $dsn, array $config)
    {
        $this->dsn = $dsn;
        $this->config = $config;
        $this->maxRetries = $config['max_retries'] ?? 3;
    }

    /**
     * Connects to the database.
     * Returns the PDO instance associated with the driver.
     * If the connection fails, it retries up to $maxRetries times with exponential backoff.
     * If all retries fail, it throws a PDOException.
     *
     * @inheritDoc
     *
     * @return PDO
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
                $options = $this->prepareOptions($this->config['options'] ?? []);
                $this->pdo = new PDO(
                    $this->dsn,
                    $this->config['username'] ?? null,
                    $this->config['password'] ?? null,
                    $options
                );

                // Set strict mode if configured
                if (!empty($this->config['strict'])) {
                    $this->pdo->exec("SET SESSION sql_mode='STRICT_ALL_TABLES'");
                }

                // Set timezone if configured
                if (!empty($this->config['timezone'])) {
                    $this->pdo->exec("SET time_zone = '" . $this->config['timezone'] . "'");
                }

                return $this->pdo;
            } catch (PDOException $e) {
                $this->lastError = $e->getMessage();
                $this->errorCode = $e->getCode();
                $attempts++;
                sleep(pow(2, $attempts - 1) / 2);
            }
        }

        throw new PDOException(
            'Failed to connect to MySQL after {' . $this->maxRetries . '} attempts: {' . $this->lastError . '}'
        );
    }

    private function prepareOptions(string|array $options): array
    {
        $optionsToArray = is_string($options)
            ? array_map('trim', preg_split('/\s*,\s*/', $options, -1, PREG_SPLIT_NO_EMPTY))
            : $options;
        $defaultOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        return $optionsToArray + $defaultOptions;
    }

    /**
     * Disconnects from the database.
     *
     * @inheritDoc
     */
    public function disconnect(): void
    {
        $this->pdo = null;
    }

    /**
     * Returns the name of the database driver.
     *
     * @inheritDoc
     */
    public function getDriverName(): string
    {
        return 'mysql';
    }

    /**
     * Returns the version of the database driver.
     *
     * @inheritDoc
     */
    public function getDriverVersion(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) : 'unknown';
    }

    /**
     * Returns the name of the connected database.
     *
     * @inheritDoc
     */
    public function getDatabaseName(): string
    {
        return $this->config['database'] ?? '';
    }

    /**
     * Returns the version of the connected database.
     *
     * @inheritDoc
     */
    public function getDatabaseVersion(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION) : 'unknown';
    }

    /**
     * Returns information about the connected database server.
     *
     * @inheritDoc
     */
    public function getServerInfo(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_SERVER_INFO) : '';
    }

    /**
     * Returns the version of the connected database server.
     *
     * @inheritDoc
     */
    public function getServerVersion(): string
    {
        return $this->pdo ? $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION) : '';
    }

    /**
     * Returns the PDO instance associated with the driver.
     *
     * @inheritDoc
     */
    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            $this->connect();
        }

        return $this->pdo;
    }

    /**
     * Retrieves a list of attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->pdo ? [
            PDO::ATTR_AUTOCOMMIT => $this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT),
            PDO::ATTR_CASE => $this->pdo->getAttribute(PDO::ATTR_CASE),
            PDO::ATTR_CLIENT_VERSION => $this->pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
        ] : [];
    }

    /**
     * Sets an attribute.
     *
     * @param int $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function setAttribute(int $attribute, mixed $value): bool
    {
        if (!$this->pdo) {
            $this->connect();
        }

        return $this->pdo->setAttribute($attribute, $value);
    }

    /**
     * Retrieves an attribute.
     *
     * @param int $attribute
     *
     * @return mixed
     */
    public function getAttribute(int $attribute): mixed
    {
        if (!$this->pdo) {
            $this->connect();
        }

        return $this->pdo->getAttribute($attribute);
    }

    /**
     * Retrieves the last error message, if available.
     *
     * @return string The last error message or an empty string if no error is recorded.
     */
    public function getLastError(): string
    {
        return $this->lastError ?? '';
    }
}
