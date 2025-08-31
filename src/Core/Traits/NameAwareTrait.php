<?php

namespace Bibo\Mvc\Core\Traits;

use Bibo\Mvc\Core\Base\App;
use Bibo\Mvc\Core\Cookie\CookieHandler;

/**
 * Trait NameAwareTrait
 *
 * Provides methods to manage a name property, including getting, setting,
 * checking existence, clearing, and various string transformations.
 */
trait NameAwareTrait
{
    /**
     * The name property.
     * This property is used to store a name as a string.
     * It can be any string value, such as a person's name, a product name, etc.
     * It is expected to be set and retrieved using the provided methods.
     * It is not nullable, and should always contain a valid string.
     * It is used in various string transformations and representations.
     * It is a fundamental part of the class that uses this trait.
     * It is not intended to be empty, but can be cleared.
     * It is used in methods that require a name representation.
     * It is a key part of the class's functionality.
     *
     * @var string
     */
    protected string $name;

    /**
     * Gets the name.
     *
     * @return string The current name.
     */
    public function getName(): string
    {
        return $this->getPrefix() . $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name The name to set.
     *
     * @return CookieHandler|App|NameAwareTrait
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Checks if the name is set and not empty.
     *
     * @return bool True if the name is set, false otherwise.
     */
    public function hasName(): bool
    {
        return !empty($this->name);
    }

    /**
     * Clears the name by setting it to an empty string.
     *
     * @return void
     */
    public function clearName(): void
    {
        $this->name = '';
    }

    /**
     * Converts the name to various formats.
     *
     * @return string The name in the specified format.
     */
    public function toLowerCase(): string
    {
        return strtolower($this->name);
    }

    /**
     * Converts the name to uppercase.
     *
     * @return string The name in uppercase.
     */
    public function toUpperCase(): string
    {
        return strtoupper($this->name);
    }

    /**
     * Converts the name to title case.
     *
     * @return string The name in title case.
     */
    public function toTitleCase(): string
    {
        return ucwords(strtolower($this->name));
    }

    /**
     * Converts the name to camel case.
     *
     * @return string The name in camel case.
     */
    public function toCamelCase(): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $this->name))));
    }

    /**
     * Converts the name to snake case.
     *
     * @return string The name in snake case.
     */
    public function toSnakeCase(): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($this->name)));
    }

    /**
     * Converts the name to kebab case.
     *
     * @return string The name in kebab case.
     */
    public function toKebabCase(): string
    {
        return strtolower(preg_replace('/[A-Z]/', '-$0', lcfirst($this->name)));
    }

    /**
     * Converts the name to Pascal case.
     *
     * @return string The name in Pascal case.
     */
    public function toPascalCase(): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $this->name)));
    }

    /**
     * Converts the name to a slug format.
     *
     * @return string The name in slug format.
     */
    public function toSlug(): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->name)));
    }

    /**
     * Converts the name to a human-readable format.
     *
     * @return string The name in a human-readable format.
     */
    public function toHumanReadable(): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $this->name));
    }

    /**
     * Converts the name to a JSON representation.
     *
     * @return string The name in JSON format.
     */
    public function toJson(): string
    {
        return json_encode(['name' => $this->name]);
    }

    /**
     * Converts the name to a string representation.
     *
     * @return string The name as a string.
     */
    public function __toString(): string
    {
        return $this->getName();
    }
}
