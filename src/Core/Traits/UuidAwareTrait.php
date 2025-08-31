<?php

namespace Bibo\Mvc\Core\Traits;

trait UuidAwareTrait
{
    protected string $uuid;

    /**
     * Get the UUID of the object.
     *
     * @return string The UUID.
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Set the UUID of the object.
     *
     * @param string $uuid The UUID to set.
     *
     * @return void
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * Check if the object has a UUID.
     *
     * @return bool True if the UUID is set, false otherwise.
     */
    public function hasUuid(): bool
    {
        return !empty($this->uuid);
    }

    /**
     * Clear the UUID of the object.
     *
     * @return void
     */
    public function clearUuid(): void
    {
        $this->uuid = '';
    }

    /**
     * Convert the object to an associative array including the UUID.
     *
     * @return array The object properties as an associative array.
     */
    public function toArray(): array
    {
        return array_merge(get_object_vars($this), ['uuid' => $this->uuid]);
    }

    /**
     * Convert the object to a string representation, including the UUID.
     *
     * @return string The string representation of the object.
     */
    public function __toString(): string
    {
        return sprintf(
            '%s (UUID: %s)',
            get_class($this),
            $this->uuid
        );
    }
}
