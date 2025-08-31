<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface PositionInterface
 *
 * This interface defines methods for managing a position, including getting,
 * setting, incrementing, decrementing, and checking the position.
 */
interface PositionInterface
{
    /**
     * Get the current position.
     *
     * @return int The current position.
     */
    public function getPosition(): int;

    /**
     * Set the current position.
     *
     * @param int $position The new position.
     *
     * @return void
     */
    public function setPosition(int $position): void;

    /**
     * Increment the current position by a specified amount.
     *
     * @param int $amount The amount to increment the position by.
     *
     * @return void
     */
    public function incrementPosition(int $amount = 1): void;

    /**
     * Decrement the current position by a specified amount.
     *
     * @param int $amount The amount to decrement the position by.
     *
     * @return void
     */
    public function decrementPosition(int $amount = 1): void;

    /**
     * Reset the position to the initial state.
     *
     * @return void
     */
    public function resetPosition(): void;

    /**
     * Check if the current position is at the start.
     *
     * @return bool True if at the start, false otherwise.
     */
    public function isAtStart(): bool;

    /**
     * Check if the current position is at the end.
     *
     * @return bool True if at the end, false otherwise.
     */
    public function isAtEnd(): bool;

    /**
     * Get the next position.
     *
     * @return int The next position.
     */
    public function getNextPosation(): int;

    /**
     * Get the previous position.
     *
     * @return int The previous position.
     */
    public function getPreviousPosition(): int;

    /**
     * Set the position to a specific value.
     *
     * @param int $position The new position.
     *
     * @return void
     */
    public function setToPosition(int $position): void;

    /**
     * Get the current position as a string.
     *
     * @return string The current position as a string.
     */
    public function getPositionAsString(): string;
}
