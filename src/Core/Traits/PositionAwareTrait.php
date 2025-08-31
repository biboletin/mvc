<?php

namespace Bibo\Mvc\Core\Traits;

use InvalidArgumentException;

trait PositionAwareTrait
{
    protected int $position = 0;

    /**
     * Get the current position.
     *
     * @return int The current position.
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Set the current position.
     *
     * @param int $position The new position.
     *
     * @return void
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * Increment the current position by a specified amount.
     *
     * @param int $amount The amount to increment the position by.
     *
     * @return void
     */
    public function incrementPosition(int $amount = 1): void
    {
        $this->position += $amount;
    }

    /**
     * Decrement the current position by a specified amount.
     *
     * @param int $amount The amount to decrement the position by.
     *
     * @return void
     *
     * @throws InvalidArgumentException If the resulting position would be negative.
     */
    public function decrementPosition(int $amount = 1): void
    {
        if ($this->position - $amount < 0) {
            throw new InvalidArgumentException('Position cannot be negative.');
        }
        $this->position -= $amount;
    }

    /**
     * Reset the current position to zero.
     *
     * @return void
     */
    public function resetPosition(): void
    {
        $this->position = 0;
    }

    /**
     * Check if the current position is at the start (zero).
     *
     * @return bool True if the position is zero, false otherwise.
     */
    public function isAtStart(): bool
    {
        return $this->position === 0;
    }

    /**
     * Check if the current position is at the end (maximum value).
     *
     * @param int $maxPosition The maximum position value.
     *
     * @return bool True if the position is at the end, false otherwise.
     */
    public function isAtEnd(int $maxPosition): bool
    {
        return $this->position >= $maxPosition;
    }

    /**
     * Get the next position, incrementing the current position.
     *
     * @return int The next position.
     */
    public function getNextPosition(): int
    {
        $this->incrementPosition();
        return $this->position;
    }

    /**
     * Get the previous position, decrementing the current position.
     *
     * @return int The previous position.
     */
    public function getPreviousPosition(): int
    {
        if ($this->position > 0) {
            $this->position--;
        }
        return $this->position;
    }

    /**
     * Set the position to a specific value.
     *
     * @param int $position The position to set.
     *
     * @return void
     */
    public function setToPosition(int $position): void
    {
        if ($position < 0) {
            throw new InvalidArgumentException('Position cannot be negative.');
        }
        $this->position = $position;
    }

    /**
     * Get the current position as a string.
     *
     * @return string The current position as a string.
     */
    public function getPositionAsString(): string
    {
        return (string)$this->position;
    }
}
