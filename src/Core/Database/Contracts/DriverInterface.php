<?php

/**
 * Defines the contract for a database driver, specifying methods required
 * for connecting, disconnecting, and retrieving information about the
 * database and its associated attributes.
 */

namespace Bibo\Mvc\Core\Database\Contracts;

use PDO;

/**
 * Interface DriverInterface
 *
 * Represents a database driver interface that defines methods for managing
 * database connections and retrieving information about the driver,
 * database, and server.
 */
interface DriverInterface
{
    /**
     * Establishes a connection to the database and returns a PDO instance.
     *
     * @return PDO The PDO instance representing the database connection.
     */
    public function connect(): PDO;

    /**
     * Closes the current database connection and releases any associated resources.
     *
     * @return void
     */
    public function disconnect(): void;

    /**
     * Retrieves the name of the driver.
     *
     * @return string The name of the driver.
     */
    public function getDriverName(): string;

    /**
     * Retrieves the version of the driver.
     *
     * @return string The version of the driver.
     */
    public function getDriverVersion(): string;

    /**
     * Retrieves the name of the database.
     *
     * @return string The name of the database.
     */
    public function getDatabaseName(): string;

    /**
     * Retrieves the version of the database.
     *
     * @return string The version of the database.
     */
    public function getDatabaseVersion(): string;

    /**
     * Retrieves information about the server.
     *
     * @return string Server information as a string.
     */
    public function getServerInfo(): string;

    /**
     * Retrieves the version of the server.
     *
     * @return string The version of the server.
     */
    public function getServerVersion(): string;
}
