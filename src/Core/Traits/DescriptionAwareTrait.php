<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait DescriptionAwareTrait
 *
 * Provides methods to manage a description property.
 */
trait DescriptionAwareTrait
{
    /**
     * The description of the object.
     *
     * @var string
     */
    protected string $description;

    /**
     * Sets the description.
     *
     * @param string $description The description to set.
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets the description.
     *
     * @return string The description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Checks if the description is set.
     *
     * @return bool True if the description is set, false otherwise.
     */
    public function hasDescription(): bool
    {
        return !empty($this->description);
    }

    /**
     * Clears the description.
     *
     * @return $this
     */
    public function clearDescription(): self
    {
        $this->description = '';
        return $this;
    }
}
