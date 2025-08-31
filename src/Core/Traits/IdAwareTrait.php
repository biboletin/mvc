<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait IdAwareTrait
 *
 * Provides methods to manage an ID for an object.
 */
trait IdAwareTrait
{
    /**
     * ID of the object.
     * This property is used to uniquely identify the object.
     * It is an integer and can be set, retrieved, checked for existence, and cleared.
     *
     * @var int
     */
    protected int $id;

    /**
     * Get the ID of the object.
     *
     * @return int The ID of the object.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the ID of the object.
     *
     * @param int $id The ID to set.
     *
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Check if the object has an ID.
     *
     * @return bool True if the ID is set, false otherwise.
     */
    public function hasId(): bool
    {
        return isset($this->id);
    }

    /**
     * Clear the ID of the object.
     *
     * @return void
     */
    public function clearId(): void
    {
        $this->id = 0;
    }
}
