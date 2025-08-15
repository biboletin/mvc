<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

enum StatusCodeCategory: string
{
    case INFORMATIONAL = 'Informational';
    case SUCCESS = 'Success';
    case REDIRECTION = 'Redirection';
    case CLIENT_ERROR = 'Client Error';
    case SERVER_ERROR = 'Server Error';

    /**
     * Creates an instance of the enum from a string value.
     * This method converts the input string to lowercase and matches it against the enum cases.
     * If the input string does not match any case, it throws an InvalidArgumentException.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException if the value does not match any status code category
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'informational' => self::INFORMATIONAL,
            'success' => self::SUCCESS,
            'redirection' => self::REDIRECTION,
            'client error' => self::CLIENT_ERROR,
            'server error' => self::SERVER_ERROR,
            default => throw new InvalidArgumentException('Invalid status code category: ' . $value),
        };
    }

    /**
     * Checks if the provided value is a valid status code category.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        try {
            self::fromString($value);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Returns the category name in a human-readable format.
     *
     * @return string
     */
    public function getReadableName(): string
    {
        return match ($this) {
            self::INFORMATIONAL => 'Informational',
            self::SUCCESS => 'Success',
            self::REDIRECTION => 'Redirection',
            self::CLIENT_ERROR => 'Client Error',
            self::SERVER_ERROR => 'Server Error',
        };
    }

    /**
     * Returns an array of all status code categories.
     *
     * @return StatusCodeCategory[]
     */
    public static function all(): array
    {
        return [
            self::INFORMATIONAL,
            self::SUCCESS,
            self::REDIRECTION,
            self::CLIENT_ERROR,
            self::SERVER_ERROR,
        ];
    }

    /**
     * Converts the enum instance to an associative array.
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
     * Returns the name of the status code category in lowercase.
     *
     * @return string
     */
    public function getLowercaseName(): string
    {
        return strtolower($this->value);
    }

    /**
     * Returns the name of the status code category in uppercase.
     *
     * @return string
     */
    public function getUppercaseName(): string
    {
        return strtoupper($this->value);
    }

    /**
     * Returns the name of the status code category in title case.
     *
     * @return string
     */
    public function getTitleCaseName(): string
    {
        return ucwords(strtolower($this->value));
    }
}
