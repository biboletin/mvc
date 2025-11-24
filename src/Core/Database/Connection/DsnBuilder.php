<?php

namespace Bibo\Mvc\Core\Database\Connection;

use InvalidArgumentException;

/**
 * Class DsnBuilder
 *
 * Builds a Data Source Name (DSN) string from a configuration array.
 * Supports MySQL, PostgreSQL, and SQLite.
 *
 * Usage:
 * ```php
 * $builder = new DsnBuilder();
 * $dsn = $builder->build([
 *     'driver' => 'mysql',
 *     'host' => '127.0.0.1',
 *     'port' => 3306,
 *     'database' => 'test',
 *     'charset' => 'utf8mb4'
 * ]);
 * ```
 *
 * @package Bibo\Mvc\Core\Database\Connection
 */
class DsnBuilder
{
    /**
     * Builds a DSN string based on the provided configuration.
     *
     * @param array $config Database configuration array with keys like 'driver', 'host', 'port', 'database', etc.
     *
     * @return string The DSN string suitable for PDO connection.
     *
     * @throws InvalidArgumentException If the driver is unsupported.
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
     * Builds a MySQL DSN string.
     *
     * Example: "mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8mb4"
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
            $config['charset']
        );
    }

    /**
     * Builds a PostgreSQL DSN string.
     *
     * Example: "pgsql:host=127.0.0.1;port=5432;dbname=test"
     *
     * @param array $config
     *
     * @return string
     */
    private function buildPostgres(array $config): string
    {
        return sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $config['host'],
            $config['port'],
            $config['database']
        );
    }

    /**
     * Builds an SQLite DSN string.
     *
     * If the SQLite database file does not exist, it creates an empty file.
     * If no database is specified, the DSN will point to a file in DATABASE_PATH with ".sqlite" extension.
     *
     * @param array $config
     *
     * @return string
     */
    private function buildSqlite(array $config): string
    {
        $databaseFile = $config['database'] ?? 'database';
        $databasePath = DATABASE_PATH . $databaseFile . '.sqlite';

        if (!file_exists($databasePath)) {
            file_put_contents($databasePath, '');
        }

        return sprintf('sqlite:%s', $databasePath);
    }
}
