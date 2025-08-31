<?php

namespace Bibo\Mvc\Core\Interfaces;

/**
 * Interface NameInterface
 *
 * This interface defines methods for handling names in various formats.
 *
 * @package Biboletin\Interfaces
 */
interface NameInterface
{
    /**
     * Get the name.
     *
     * @return string The name.
     */
    public function getName(): string;

    /**
     * Set the name.
     *
     * @param string $name The name to set.
     *
     * @return self
     */
    public function setName(string $name): self;

    /**
     * Check if the name is set.
     *
     * @return bool True if the name is set, false otherwise.
     */
    public function hasName(): bool;

    /**
     * Clear the name.
     *
     * @return self
     */
    public function clearName(): self;

    /**
     * Convert the name to various formats.
     *
     * @return string The name in the specified format.
     */
    public function toLowerCase(): string;

    /**
     * Convert the name to uppercase.
     *
     * @return string The name in uppercase.
     */
    public function toUpperCase(): string;

    /**
     * Convert the name to title case.
     *
     * @return string The name in title case.
     */
    public function toTitleCase(): string;

    /**
     * Convert the name to snake case.
     *
     * @return string The name in snake case.
     */
    public function toSnakeCase(): string;

    /**
     * Convert the name to kebab case.
     *
     * @return string The name in kebab case.
     */
    public function toKebabCase(): string;

    /**
     * Convert the name to camel case.
     *
     * @return string The name in camel case.
     */
    public function toCamelCase(): string;

    /**
     * Convert the name to pascal case.
     *
     * @return string The name in pascal case.
     */
    public function toPascalCase(): string;

    /**
     * Convert the name to a slug.
     *
     * @return string The name as a slug.
     */
    public function toSlug(): string;

    /**
     * Convert the name to a human-readable format.
     *
     * @return string The name in a human-readable format.
     */
    public function toHumanReadable(): string;

    /**
     * Convert the name to a JSON string.
     *
     * @return string The name in JSON format.
     */
    public function toJson(): string;
}
