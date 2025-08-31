<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

/**
 * CacheDriver enum class
 * This enum represents different cache drivers that can be used in the application.
 * It provides methods to create instances from strings, check validity, and retrieve
 * driver names in different formats.
 */
enum CacheDriver: string
{
    case APCU = 'apcu';
    case FILE = 'file';
    case MEMORY = 'memory';
    case REDIS = 'redis';
    case MEMCACHED = 'memcached';
    case DATABASE = 'database';

    /**
     * Creates an instance of the enum from a string value.
     * This method converts the input string to lowercase and matches it against the enum cases.
     * If the input string does not match any case, it throws an InvalidArgumentException.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException if the value does not match any cache driver
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'apcu' => self::APCU,
            'file' => self::FILE,
            'memory' => self::MEMORY,
            'redis' => self::REDIS,
            'memcached' => self::MEMCACHED,
            'database' => self::DATABASE,
            default => throw new InvalidArgumentException('Invalid cache driver: ' . $value),
        };
    }

    /**
     * Checks if the provided value is a valid cache driver.
     * This method compares the input string (converted to lowercase) against all defined enum cases.
     * It returns true if the value matches any of the enum cases, otherwise false.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException if the value is not a valid cache driver
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        return in_array(
            strtolower($value),
            array_map(fn ($case) => strtolower($case->value), self::cases()),
            true
        );
    }

    /**
     * Returns an array of all cache driver enum instances.
     * This method provides a convenient way to retrieve all defined cache drivers
     * as an array of CacheDriver instances.
     *
     * @return CacheDriver[]
     */
    public static function all(): array
    {
        return [
            self::APCU,
            self::FILE,
            self::MEMORY,
            self::REDIS,
            self::MEMCACHED,
            self::DATABASE,
        ];
    }

    /**
     * Converts the enum instance to an associative array.
     * This method returns an array representation of the enum instance,
     * including its name and value.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }

    /**
     * Returns the name of the cache driver in lowercase.
     * This method converts the enum value to lowercase and returns it.
     * This is useful for case-insensitive comparisons or storage.
     *
     * @return string
     */
    public function getLowercaseName(): string
    {
        return strtolower($this->value);
    }

    /**
     * Returns the name of the cache driver in uppercase.
     * This method converts the enum value to uppercase and returns it.
     * This is useful for case-insensitive comparisons or storage.
     *
     * @return string
     */
    public function getUppercaseName(): string
    {
        return strtoupper($this->value);
    }

    /**
     * Returns the name of the cache driver in title case.
     * This method converts the enum value to title case and returns it.
     * This is useful for display purposes where a more readable format is desired.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the value of the cache driver.
     * This method returns the string value associated with the enum instance.
     * This is useful for retrieving the actual string representation of the cache driver.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns the lowercase value of the cache driver.
     * This method converts the enum value to lowercase and returns it.
     * This is useful for case-insensitive comparisons or storage.
     *
     * @return string
     */
    public function getLowercaseValue(): string
    {
        return strtolower($this->value);
    }

    /**
     * Returns the uppercase value of the cache driver.
     * This method converts the enum value to uppercase and returns it.
     * This is useful for case-insensitive comparisons or storage.
     *
     * @return string
     */
    public function getUppercaseValue(): string
    {
        return strtoupper($this->value);
    }

    /**
     * Returns the title case value of the cache driver.
     * This method converts the enum value to title case and returns it.
     * This is useful for display purposes where a more readable format is desired.
     *
     * @return string
     */
    public function getTitleCaseValue(): string
    {
        return ucwords(strtolower($this->value));
    }
}
