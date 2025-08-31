<?php

namespace Bibo\Mvc\Core\Traits;

trait StatusAwareTrait
{
    protected string $status;

    /**
     * Sets the status of the object.
     *
     * @param string $status The status to set.
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Gets the status of the object.
     *
     * @return string The current status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Checks if the object has a specific status.
     *
     * @param string $status The status to check.
     *
     * @return bool True if the object has the specified status, false otherwise.
     */
    public function hasStatus(string $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Checks if the object has a status that is not empty.
     *
     * @return bool True if the status is not empty, false otherwise.
     */
    public function hasStatusNotEmpty(): bool
    {
        return !empty($this->status);
    }

    /**
     * Checks if the object has a status that is empty.
     *
     * @return bool True if the status is empty, false otherwise.
     */
    public function hasStatusEmpty(): bool
    {
        return empty($this->status);
    }

    /**
     * Checks if the object has a status that is not null.
     *
     * @return bool True if the status is not null, false otherwise.
     */
    public function hasStatusNotNull(): bool
    {
        return $this->status !== null;
    }

    /**
     * Checks if the object has a status that is null.
     *
     * @return bool True if the status is null, false otherwise.
     */
    public function hasStatusNull(): bool
    {
        return $this->status === null;
    }
}
