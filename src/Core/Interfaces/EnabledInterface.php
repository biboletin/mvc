<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface EnabledInterface
 *
 * Provides methods to manage the enabled state of an object.
 */
interface EnabledInterface
{
    /**
     * Gets the current enabled state of the object.
     *
     * @return bool Returns true if the object is enabled, false otherwise.
     */
    public function isEnabled(): bool;

    /**
     * Sets the enabled state of the object.
     *
     * @param bool $enabled The new enabled state to set.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function setEnabled(bool $enabled): self;

    /**
     * Toggles the enabled state of the object.
     * If the object is currently enabled, it will be disabled, and vice versa.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function toggleEnabled(): self;

    /**
     * Enables the object, setting its state to enabled.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function enable(): self;

    /**
     * Disables the object, setting its state to disabled.
     *
     * @return self Returns the current instance for method chaining.
     */
    public function disable(): self;

    /**
     * Checks if the object is currently enabled.
     *
     * @return bool Returns true if the object is enabled, false otherwise.
     */
    public function isDisabled(): bool;
}
