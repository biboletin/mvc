<?php

namespace Bibo\Mvc\Core\Interfaces;

interface UuidInterface
{
    /**
     * Get the UUID of the object.
     *
     * @return string The UUID.
     */
    public function getUuid(): string;

    /**
     * Set the UUID of the object.
     *
     * @param string $uuid The UUID to set.
     *
     * @return void
     */
    public function setUuid(string $uuid): void;

    /**
     * Check if the object has a UUID.
     *
     * @return bool True if the UUID is set, false otherwise.
     */
    public function hasUuid(): bool;

    /**
     * Clear the UUID of the object.
     *
     * @return void
     */
    public function clearUuid(): void;

    /**
     * Convert the object to an associative array including the UUID.
     *
     * @return array The object properties as an associative array.
     */
    public function toArray(): array;

    /**
     * Convert the object to a string representation, including the UUID.
     *
     * @return string The string representation of the object.
     */
    public function __toString(): string;
}
