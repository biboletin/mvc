<?php

namespace Bibo\Mvc\Core\Database\Contracts;

use PDO;

interface PdoDriverInterface extends DriverInterface
{
    /**
     * Retrieves the PDO instance.
     *
     * @return PDO The PDO instance.
     */
    public function getPdo(): PDO;

    /**
     * Retrieves a list of attributes.
     *
     * @return array An array of attributes.
     */
    public function getAttributes(): array;

    /**
     * Sets an attribute to the given value.
     *
     * @param int $attribute The attribute identifier to be set.
     * @param mixed $value The value to assign to the specified attribute.
     *
     * @return bool Returns true on success or false on failure.
     */
    public function setAttribute(int $attribute, mixed $value): bool;

    /**
     * Retrieves the value of the specified attribute.
     *
     * @param int $attribute The identifier of the attribute to be retrieved.
     *
     * @return mixed The value of the requested attribute.
     */
    public function getAttribute(int $attribute): mixed;

    /**
     * Retrieves the last error message encountered.
     *
     * @return string The last error message as a string.
     */
    public function getLastError(): string;
}
