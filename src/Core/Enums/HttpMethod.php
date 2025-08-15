<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

/**
 * HTTP Method Enum
 *
 * This enum represents the various HTTP methods used in web requests.
 * It provides methods to check the characteristics of each HTTP method,
 * such as whether it is safe, idempotent, cacheable, a write operation, or a read operation.
 */
enum HttpMethod: string
{
    case GET = 'GET';

    case POST = 'POST';

    case PUT = 'PUT';

    case DELETE = 'DELETE';

    case PATCH = 'PATCH';

    case HEAD = 'HEAD';

    case OPTIONS = 'OPTIONS';

    case CONNECT = 'CONNECT';

    case TRACE = 'TRACE';

    case ANY = 'ANY';

    /**
     * Check if the method is safe (does not modify server state)
     *
     * @return bool
     */

    public function isSafe(): bool
    {
        return in_array($this, [self::GET, self::HEAD, self::OPTIONS, self::TRACE]);
    }

    /**
     * Check if the method is idempotent (can be called multiple times without different outcomes)
     *
     * @return bool
     */

    public function isIdempotent(): bool
    {
        return in_array($this, [self::GET, self::PUT, self::DELETE, self::HEAD, self::OPTIONS, self::TRACE]);
    }

    /**
     * Check if the method is cacheable
     *
     * @return bool
     */

    public function isCacheable(): bool
    {
        return in_array($this, [self::GET, self::HEAD, self::OPTIONS]);
    }

    /**
     * Check if the method is a write operation
     *
     * @return bool
     */

    public function isWriteOperation(): bool
    {
        return in_array($this, [self::POST, self::PUT, self::DELETE, self::PATCH]);
    }

    /**
     * Check if the method is a read operation
     *
     * @return bool
     */

    public function isReadOperation(): bool
    {
        return in_array($this, [self::GET, self::HEAD, self::OPTIONS, self::TRACE]);
    }

    /**
     * Check if the method is a valid HTTP method
     *
     * @return bool
     */

    public function isValid(): bool
    {
        return in_array($this, self::cases());
    }

    /**
     * Create an instance from a string
     *
     * @param string $method
     *
     * @return self
     * @throws InvalidArgumentException if the method is not valid
     */

    public static function fromString(string $method): self
    {
        $method = strtoupper($method);
        foreach (self::cases() as $httpMethod) {
            if ($httpMethod->value === $method) {
                return $httpMethod;
            }
        }
        throw new InvalidArgumentException('Invalid HTTP method: ' . $method);
    }

    /**
     * Get all HTTP methods as an array
     *
     * @return array
     */

    public static function all(): array
    {
        return array_map(fn ($method) => $method->value, self::cases());
    }

    /**
     * Check if the method is a valid HTTP method
     *
     * @param string $method
     *
     * @return bool
     */

    public static function isValidMethod(string $method): bool
    {
        try {
            self::fromString($method);
            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Get the HTTP method from the server request
     *
     * @return self
     */

    public static function fromServerRequest(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET'; // Default to GET if not set
        return self::fromString($method);
    }

    /**
     * Get the HTTP method from the server request as a string
     *
     * @return string
     */

    public static function fromServerRequestString(): string
    {
        return self::fromServerRequest()->value;
    }

    /**
     * Get the HTTP method from the server request as a string
     *
     * @return string
     */

    public static function fromServerRequestUpperCase(): string
    {
        return strtoupper(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCase(): string
    {
        return strtolower(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseString(): string
    {
        return strtolower(self::fromServerRequestString());
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestUpperCaseString(): string
    {
        return strtoupper(self::fromServerRequestString());
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseValue(): string
    {
        return strtolower(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as an uppercase string
     *
     * @return string
     */

    public static function fromServerRequestUpperCaseValue(): string
    {
        return strtoupper(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseMethod(): string
    {
        return strtolower(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as an uppercase string
     *
     * @return string
     */

    public static function fromServerRequestUpperCaseMethod(): string
    {
        return strtoupper(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseMethodString(): string
    {
        return strtolower(self::fromServerRequestString());
    }

    /**
     * Get the HTTP method from the server request as an uppercase string
     *
     * @return string
     */

    public static function fromServerRequestUpperCaseMethodString(): string
    {
        return strtoupper(self::fromServerRequestString());
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseMethodValue(): string
    {
        return strtolower(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as an uppercase string
     *
     * @return string
     */

    public static function fromServerRequestUpperCaseMethodValue(): string
    {
        return strtoupper(self::fromServerRequest()->value);
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseMethodValueString(): string
    {
        return strtolower(self::fromServerRequestString());
    }

    /**
     * Get the HTTP method from the server request as an uppercase string
     *
     * @return string
     */

    public static function fromServerRequestUpperCaseMethodValueString(): string
    {
        return strtoupper(self::fromServerRequestString());
    }

    /**
     * Get the HTTP method from the server request as a lowercase string
     *
     * @return string
     */

    public static function fromServerRequestLowerCaseMethodValueUpperCase(): string
    {
        return strtolower(self::fromServerRequest()->value);
    }
}
