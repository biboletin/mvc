<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait EnabledAwareTrait
 *
 * Provides methods to manage the enabled state of an object.
 */
trait EnabledAwareTrait
{
    /**
     * Indicates whether the object is enabled.
     * This property is used to track the enabled state of the object.
     * It defaults to true, meaning the object is enabled by default.
     *
     * @var bool
     */
    protected bool $enabled = true;

    /**
     * Gets the current enabled state of the object.
     *
     * @return bool Returns true if the object is enabled, false otherwise.
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Sets the enabled state of the object.
     *
     * @param bool $enabled The new enabled state to set.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Toggles the enabled state of the object.
     * If the object is currently enabled, it will be disabled, and vice versa.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function toggleEnabled(): self
    {
        $this->enabled = !$this->enabled;
        return $this;
    }

    /**
     * Enables the object, setting its state to enabled.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function enable(): self
    {
        $this->enabled = true;
        return $this;
    }

    /**
     * Disables the object, setting its state to disabled.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function disable(): self
    {
        $this->enabled = false;
        return $this;
    }

    /**
     * Checks if the object is disabled.
     *
     * @return bool Returns true if the object is disabled, false if it is enabled.
     */
    public function isDisabled(): bool
    {
        return !$this->enabled;
    }
}
