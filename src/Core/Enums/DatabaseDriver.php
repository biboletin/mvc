<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

/**
 * DatabaseDriver enum class
 *
 * This enum represents various database drivers that can be used in an application.
 * It provides methods to create instances from strings, check validity, and retrieve
 * driver names in different formats.
 */
enum DatabaseDriver: string
{
    case MYSQL = 'mysql';
    case POSTGRESQL = 'postgresql';
    case SQLITE = 'sqlite';
    case MONGODB = 'mongodb';
    case SQLSERVER = 'sqlserver';
    case ORACLE = 'oracle';
    case REDIS = 'redis';
    case CASSANDRA = 'cassandra';
    case DYNAMODB = 'dynamodb';
    case COUCHBASE = 'couchbase';
    case ELASTICSEARCH = 'elasticsearch';
    case CLICKHOUSE = 'clickhouse';
    case MARIADB = 'mariadb';
    case COCKROACHDB = 'cockroachdb';
    case FIREBIRD = 'firebird';

    /**
     * Creates an instance of the enum from a string value.
     *
     * @param string $value
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'mysql' => self::MYSQL,
            'postgresql' => self::POSTGRESQL,
            'sqlite' => self::SQLITE,
            'mongodb' => self::MONGODB,
            'sqlserver' => self::SQLSERVER,
            'oracle' => self::ORACLE,
            'redis' => self::REDIS,
            'cassandra' => self::CASSANDRA,
            'dynamodb' => self::DYNAMODB,
            'couchbase' => self::COUCHBASE,
            'elasticsearch' => self::ELASTICSEARCH,
            'clickhouse' => self::CLICKHOUSE,
            'mariadb' => self::MARIADB,
            'cockroachdb' => self::COCKROACHDB,
            'firebird' => self::FIREBIRD,
            default => throw new InvalidArgumentException('Invalid database driver: ' . $value),
        };
    }

    /**
     * Checks if the enum value is a valid database driver.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        try {
            self::fromString($value);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Returns the list of all database drivers.
     *
     * @return array
     */
    public static function getAllDrivers(): array
    {
        return [
            self::MYSQL,
            self::POSTGRESQL,
            self::SQLITE,
            self::MONGODB,
            self::SQLSERVER,
            self::ORACLE,
            self::REDIS,
            self::CASSANDRA,
            self::DYNAMODB,
            self::COUCHBASE,
            self::ELASTICSEARCH,
            self::CLICKHOUSE,
            self::MARIADB,
            self::COCKROACHDB,
            self::FIREBIRD,
        ];
    }

    /**
     * Returns the database driver name in lowercase.
     *
     * @return string
     */
    public function getLowercaseName(): string
    {
        return strtolower($this->value);
    }

    /**
     * Returns the database driver name in uppercase.
     *
     * @return string
     */
    public function getUppercaseName(): string
    {
        return strtoupper($this->value);
    }

    /**
     * Returns the database driver name in title case.
     *
     * @return string
     */
    public function getTitleCaseName(): string
    {
        return ucwords(strtolower($this->value));
    }

    /**
     * Returns the database driver name in snake case.
     *
     * @return string
     */
    public function getSnakeCaseName(): string
    {
        return strtolower(str_replace(' ', '_', $this->value));
    }

    /**
     * Returns the database driver name in kebab case.
     *
     * @return string
     */
    public function getKebabCaseName(): string
    {
        return strtolower(str_replace(' ', '-', $this->value));
    }
}
