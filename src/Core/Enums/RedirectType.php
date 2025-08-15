<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

enum RedirectType: int
{
    case TEMPORARY = 302;
    case PERMANENT = 301;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case USE_PROXY = 305;
    case SWITCH_PROXY = 306;
    case TEMPORARY_REDIRECT = 307;
    case PERMANENT_REDIRECT = 308;
    case MULTIPLE_CHOICES = 300;

    /**
     * Creates an instance of the enum from an integer value.
     *
     * @param int $value
     *
     * @return self
     */
    public static function fromInt(int $value): self
    {
        return match ($value) {
            301 => self::PERMANENT,
            302 => self::TEMPORARY,
            303 => self::SEE_OTHER,
            304 => self::NOT_MODIFIED,
            305 => self::USE_PROXY,
            306 => self::SWITCH_PROXY,
            307 => self::TEMPORARY_REDIRECT,
            308 => self::PERMANENT_REDIRECT,
            300 => self::MULTIPLE_CHOICES,
            default => throw new InvalidArgumentException('Invalid redirect type: ' . $value),
        };
    }
    /**
     * Checks if the provided value is a valid redirect type.
     *
     * @param int $value
     *
     * @return bool
     */
    public static function isValid(int $value): bool
    {
        try {
            self::fromInt($value);
            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Returns the redirect type as an integer.
     *
     * @return int
     */
    public function toInt(): int
    {
        return $this->value;
    }

    /**
     * Returns the redirect type as a string.
     *
     * @return string
     */
    public function toString(): string
    {
        return match ($this) {
            self::TEMPORARY => 'Temporary Redirect',
            self::PERMANENT => 'Permanent Redirect',
            self::SEE_OTHER => 'See Other',
            self::NOT_MODIFIED => 'Not Modified',
            self::USE_PROXY => 'Use Proxy',
            self::SWITCH_PROXY => 'Switch Proxy',
            self::TEMPORARY_REDIRECT => 'Temporary Redirect',
            self::PERMANENT_REDIRECT => 'Permanent Redirect',
            self::MULTIPLE_CHOICES => 'Multiple Choices',
        };
    }

    /**
     * Returns an array of all redirect types.
     *
     * @return RedirectType[]
     */
    public static function all(): array
    {
        return [
            self::TEMPORARY,
            self::PERMANENT,
            self::SEE_OTHER,
            self::NOT_MODIFIED,
            self::USE_PROXY,
            self::SWITCH_PROXY,
            self::TEMPORARY_REDIRECT,
            self::PERMANENT_REDIRECT,
            self::MULTIPLE_CHOICES,
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
}
