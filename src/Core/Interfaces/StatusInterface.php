<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface StatusInterface
 *
 * This interface defines methods for managing the status of an object.
 */
interface StatusInterface
{
    /**
     * Sets the status of the object.
     *
     * @param string $status The status to set.
     */
    public function setStatus(string $status): void;

    /**
     * Gets the status of the object.
     *
     * @return string The current status.
     */
    public function getStatus(): string;

    /**
     * Checks if the object has a specific status.
     *
     * @param string $status The status to check.
     *
     * @return bool True if the object has the specified status, false otherwise.
     */
    public function hasStatus(string $status): bool;

    /**
     * Checks if the object has a status that is not empty.
     *
     * @return bool True if the status is not empty, false otherwise.
     */
    public function hasStatusNotEmpty(): bool;

    /**
     * Checks if the object has a status that is empty.
     *
     * @return bool True if the status is empty, false otherwise.
     */
    public function hasStatusEmpty(): bool;

    /**
     * Checks if the object has a status that is not null.
     *
     * @return bool True if the status is not null, false otherwise.
     */
    public function hasStatusNotNull(): bool;

    /**
     * Checks if the object has a status that is null.
     *
     * @return bool True if the status is null, false otherwise.
     */
    public function hasStatusNull(): bool;
}
