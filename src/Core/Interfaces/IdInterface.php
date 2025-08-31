<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface IdInterface
 *
 * Provides methods to manage an ID for an object.
 */
interface IdInterface
{
    /**
     * Get the ID of the object.
     *
     * @return string Returns the ID of the object.
     */
    public function getId(): string;

    /**
     * Set the ID of the object.
     *
     * @param int $id The ID to set.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function setId(int $id): self;

    /**
     * Check if the object has an ID.
     *
     * @return bool Returns true if the ID is set, false otherwise.
     */
    public function hasId(): bool;

    /**
     * Clear the ID of the object.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function clearId(): self;
}
