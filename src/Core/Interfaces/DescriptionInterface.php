<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface DescriptionInterface
 *
 * Provides methods to manage a description property.
 */
interface DescriptionInterface
{
    /**
     * Sets the description.
     *
     * @param string $description The description to set.
     *
     * @return $this
     */
    public function setDescription(string $description): self;

    /**
     * Gets the description.
     *
     * @return string The description.
     */
    public function getDescription(): string;

    /**
     * Checks if the description is set.
     *
     * @return bool True if the description is set, false otherwise.
     */
    public function hasDescription(): bool;

    /**
     * Clears the description.
     *
     * @return $this
     */
    public function clearDescription(): self;
}
