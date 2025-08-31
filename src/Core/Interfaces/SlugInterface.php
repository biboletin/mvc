<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface SlugInterface
 *
 * Provides methods to manage a slug property.
 */
interface SlugInterface
{
    /**
     * The slug property.
     * This property is used to store a unique identifier for the object, typically used in URLs.
     * It should be a URL-friendly string that uniquely identifies the object.
     * It is often used in web applications to create clean and readable URLs.
     * It is recommended to use lowercase letters, numbers, and hyphens.
     * It should not contain spaces or special characters.
     * It is typically generated from the object's title or name.
     * It should be unique across the application to avoid conflicts.
     * It is often used in conjunction with a database to retrieve the object based on the slug.
     *
     * @return string
     */
    public function getSlug(): string;

    /**
     * Sets the slug property.
     *
     * @param string $slug The slug to set. It should be a URL-friendly string.
     */
    public function setSlug(string $slug): void;

    /**
     * Checks if the slug property is set.
     *
     * @return bool Returns true if the slug is not empty, false otherwise.
     */
    public function hasSlug(): bool;

    /**
     * Clears the slug property.
     *
     * This method sets the slug to an empty string, effectively removing any existing value.
     */
    public function clearSlug(): void;

    /**
     * Checks if the slug is empty.
     *
     * @return bool Returns true if the slug is empty, false otherwise.
     */
    public function isSlugEmpty(): bool;

    /**
     * Checks if the slug is not empty.
     *
     * @return bool Returns true if the slug is not empty, false otherwise.
     */
    public function isSlugNotEmpty(): bool;

    /**
     * Checks if the slug is equal to a given slug.
     *
     * @param string $slug The slug to compare with.
     *
     * @return bool Returns true if the slugs are equal, false otherwise.
     */
    public function isSlugEqualTo(string $slug): bool;
}
