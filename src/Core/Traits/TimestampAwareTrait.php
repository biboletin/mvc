<?php

namespace Bibo\Mvc\Core\Traits;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Trait TimestampAwareTrait
 *
 * Provides methods to manage timestamps for created, updated, and deleted entities.
 */
trait TimestampAwareTrait
{
    /**
     * Created at timestamp.
     * This property is used to track when the entity was created.
     * It is nullable to allow for entities that may not have been created yet.
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $createdAt = null;

    /**
     * Updated at timestamp.
     * This property is used to track when the entity was last updated.
     * It is nullable to allow for entities that may not have been updated yet.
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $updatedAt = null;

    /**
     * Deleted at timestamp.
     * This property is used to track when the entity was deleted.
     * It is nullable to allow for entities that may not have been deleted yet.
     *
     * @var DateTimeInterface|null
     */
    protected ?DateTimeInterface $deletedAt = null;

    /**
     * Getters and Setters for createdAt, updatedAt, and deletedAt properties.
     */

    /**
     * Get the createdAt timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the createdAt timestamp.
     *
     * @param DateTimeInterface $createdAt
     *
     * @return self
     */
    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get the updatedAt timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the updatedAt timestamp.
     *
     * @param DateTimeInterface $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get the deletedAt timestamp.
     *
     * @return DateTimeInterface|null
     */
    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    /**
     * Set the deletedAt timestamp.
     *
     * @param DateTimeInterface|null $deletedAt
     *
     * @return self
     */
    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Check if the entity is created, updated, or deleted.
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * Check if the entity is not deleted.
     *
     * @return bool
     */
    public function isNotDeleted(): bool
    {
        return !$this->isDeleted();
    }

    /**
     * Check if the entity is created or updated.
     *
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->createdAt !== null;
    }

    /**
     * Check if the entity is updated.
     *
     * @return bool
     */
    public function isUpdated(): bool
    {
        return $this->updatedAt !== null;
    }

    /**
     * Check if the entity is not created.
     *
     * @return bool
     */
    public function isNotCreated(): bool
    {
        return !$this->isCreated();
    }

    /**
     * Check if the entity is not updated.
     *
     * @return bool
     */
    public function isNotUpdated(): bool
    {
        return !$this->isUpdated();
    }

    /**
     * Check if the entity has any timestamps set (created, updated, or deleted).
     *
     * @return bool
     */
    public function isTimestamped(): bool
    {
        return $this->isCreated() || $this->isUpdated() || $this->isDeleted();
    }

    /**
     * Check if the entity does not have any timestamps set.
     *
     * @return bool
     */
    public function isNotTimestamped(): bool
    {
        return !$this->isTimestamped();
    }

    /**
     * Reset all timestamps to null.
     *
     * @return self
     */
    public function resetTimestamps(): self
    {
        $this->createdAt = null;
        $this->updatedAt = null;
        $this->deletedAt = null;
        return $this;
    }

    /**
     * Touch the entity to update the updatedAt timestamp.
     * If createdAt is not set, it will also set createdAt to the current time.
     *
     * @return self
     */
    public function touch(): self
    {
        $this->updatedAt = new DateTimeImmutable();
        if ($this->createdAt === null) {
            $this->createdAt = $this->updatedAt;
        }
        return $this;
    }

    /**
     * Touch the createdAt, updatedAt, and deletedAt timestamps.
     *
     * @return self
     */
    public function touchCreated(): self
    {
        $this->createdAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Touch the updatedAt timestamp.
     *
     * @return self
     */
    public function touchUpdated(): self
    {
        $this->updatedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Touch the deletedAt timestamp.
     *
     * @return self
     */
    public function touchDeleted(): self
    {
        $this->deletedAt = new DateTimeImmutable();
        return $this;
    }

    /**
     * Convert the timestamps to a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            'Created at: %s, Updated at: %s, Deleted at: %s',
            $this->createdAt ? $this->createdAt->format('Y-m-d H:i:s') : 'null',
            $this->updatedAt ? $this->updatedAt->format('Y-m-d H:i:s') : 'null',
            $this->deletedAt ? $this->deletedAt->format('Y-m-d H:i:s') : 'null'
        );
    }
}
