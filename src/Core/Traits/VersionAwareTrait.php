<?php

namespace Bibo\Mvc\Core\Traits;

/**
 * Trait VersionAwareTrait
 *
 * Provides version-handling capabilities for classes that require
 * tracking, validating, and manipulating a version string.
 *
 * This trait offers a simple, consistent API for:
 *  - retrieving and updating a version value,
 *  - clearing the stored version,
 *  - checking whether a version is set or empty.
 */
trait VersionAwareTrait
{
    /**
     * The version string associated with the implementing class.
     *
     * @var string
     */
    protected string $version;

    /**
     * Retrieve the currently assigned version string.
     *
     * @return string The stored version value.
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set or update the version value.
     *
     * @param string $version The version string to assign.
     *
     * @return void
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    /**
     * Clear the stored version value.
     *
     * This resets the version to an empty string, effectively marking it
     * as unset for methods that check emptiness.
     *
     * @return void
     */
    public function clearVersion(): void
    {
        $this->version = '';
    }

    /**
     * Determine whether the stored version string is empty.
     *
     * @return bool True if the version is an empty string, false otherwise.
     */
    public function isVersionEmpty(): bool
    {
        return empty($this->version);
    }

    /**
     * Determine whether the stored version string is not empty.
     *
     * @return bool True if a non-empty version string is stored, false otherwise.
     */
    public function isVersionNotEmpty(): bool
    {
        return !empty($this->version);
    }

    /**
     * Check whether the version property has been set.
     *
     * This only checks property existence, not its content.
     *
     * @return bool True if the version property is initialized, false otherwise.
     */
    public function hasVersion(): bool
    {
        return isset($this->version);
    }
}
