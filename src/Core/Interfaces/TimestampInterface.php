<?php

namespace Bibo\Mvc\Core\Interfaces;

use DateTimeInterface;

interface TimestampInterface
{
    /**
     * Get the createdAt timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface;

    /**
     * Set the createdAt timestamp.
     *
     * @param DateTimeInterface $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self;

    /**
     * Get the updatedAt timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface;

    /**
     * Set the updatedAt timestamp.
     *
     * @param DateTimeInterface $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self;

    /**
     * Get the deletedAt timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface;

    /**
     * Set the deletedAt timestamp.
     *
     * @param DateTimeInterface|null $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(?DateTimeInterface $deletedAt): self;

    /**
     * Check if the entity is created, updated, or deleted.
     */
    public function isDeleted(): bool;

    /**
     * Check if the entity is not deleted.
     *
     * @return bool
     */
    public function isNotDeleted(): bool;

    /**
     * Check if the entity is created or updated.
     *
     * @return bool
     */
    public function isCreated(): bool;

    /**
     * Check if the entity is updated.
     *
     * @return bool
     */
    public function isUpdated(): bool;

    /**
     * Check if the entity is not created.
     *
     * @return bool
     */
    public function isNotCreated(): bool;

    /**
     * Check if the entity is not updated.
     *
     * @return bool
     */
    public function isNotUpdated(): bool;

    /**
     * Check if the entity has any timestamps set (created, updated, or deleted).
     *
     * @return bool
     */
    public function isTimestamped(): bool;

    /**
     * Check if the entity does not have any timestamps set.
     *
     * @return bool
     */
    public function isNotTimestamped(): bool;

    /**
     * Reset all timestamps to null.
     *
     * @return self
     */
    public function resetTimestamps(): self;

    /**
     * Touch the entity to update the updatedAt timestamp.
     * If createdAt is not set, it will also set createdAt to the current time.
     *
     * @return self
     */
    public function touch(): self;

    /**
     * Touch the createdAt, updatedAt, and deletedAt timestamps.
     *
     * @return self
     */
    public function touchCreated(): self;

    /**
     * Touch the updatedAt timestamp.
     *
     * @return self
     */
    public function touchUpdated(): self;

    /**
     * Touch the deletedAt timestamp.
     *
     * @return self
     */
    public function touchDeleted(): self;

    /**
     * Convert the timestamps to a string representation.
     *
     * @return string
     */
    public function __toString(): string;
}
