<?php

namespace Bibo\Mvc\Core\Database\Drivers;

use Bibo\Mvc\Core\Database\Contracts\DriverInterface;
use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;
use PDO;

class PgSqlDriver implements DriverInterface, PdoDriverInterface
{
    public function __construct(string $dsn, array $config)
    {
    }

    /**
     * @inheritDoc
     */
    public function connect(): PDO
    {
        // TODO: Implement connect() method.
    }

    /**
     * @inheritDoc
     */
    public function disconnect(): void
    {
        // TODO: Implement disconnect() method.
    }

    /**
     * @inheritDoc
     */
    public function getDriverName(): string
    {
        // TODO: Implement getDriverName() method.
    }

    /**
     * @inheritDoc
     */
    public function getDriverVersion(): string
    {
        // TODO: Implement getDriverVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function getDatabaseName(): string
    {
        // TODO: Implement getDatabaseName() method.
    }

    /**
     * @inheritDoc
     */
    public function getDatabaseVersion(): string
    {
        // TODO: Implement getDatabaseVersion() method.
    }

    /**
     * @inheritDoc
     */
    public function getServerInfo(): string
    {
        // TODO: Implement getServerInfo() method.
    }

    /**
     * Get the version of the PostgreSQL server.
     *
     * @inheritDoc
     */
    public function getServerVersion(): string
    {
        // TODO: Implement getServerVersion() method.
    }

    public function getPdo(): PDO
    {
        // TODO: Implement getPdo() method.
    }

    public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
    }

    public function setAttribute(int $attribute, mixed $value): bool
    {
        // TODO: Implement setAttribute() method.
    }

    public function getAttribute(int $attribute): mixed
    {
        // TODO: Implement getAttribute() method.
    }

    public function getLastError(): string
    {
        // TODO: Implement getLastError() method.
    }
}