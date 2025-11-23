<?php

namespace Bibo\Mvc\Core\Database\Connection;

use InvalidArgumentException;

/**
 * Class DsnBuilder
 * Builds a Data Source Name (DSN) based on the provided configuration.
 * Supports MySQL, PostgreSQL, and SQLite databases.
 *
 * @package Bibo\Mvc\Core\Database\Connection
 */
class DsnBuilder
{
    /**
     * Builds a Data Source Name (DSN) based on the provided configuration.
     *
     * @param array $config
     *
     * @return string
     */
    public function build(array $config = []): string
    {
        $driver = $config['driver'] ?? null;

        return match ($driver) {
            'pdo', 'mariadb', 'mysql' => $this->buildMysql($config),
            'pgsql', 'postgres', 'postgresql' => $this->buildPostgres($config),
            'sqlite' => $this->buildSqlite($config),
            default => throw new InvalidArgumentException('Unsupported driver: ' . $driver),
        };
    }

    /**
     * Builds a MySQL Data Source Name (DSN) based on the provided configuration.
     *
     * @param array $config
     *
     * @return string
     */
    private function buildMysql(array $config): string
    {
        return sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset'],
        );
    }

    /**
     * Builds a PostgreSQL Data Source Name (DSN) based on the provided configuration.
     *
     * @param array $config
     *
     * @return string
     */
    private function buildPostgres(array $config): string
    {
        return sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['username'],
            $config['password'],
        );
    }

    /**
     * Builds an SQLite Data Source Name (DSN) based on the provided configuration.
     *
     * @param array $config
     *
     * @return string
     */
    private function buildSqlite(array $config): string
    {
        $path = $config['database'] ?? ':memory:';

        return sprintf('sqlite:%s', $path);
    }
}
