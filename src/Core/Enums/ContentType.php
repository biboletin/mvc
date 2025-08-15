<?php

namespace Bibo\Mvc\Core\Enums;

use InvalidArgumentException;

enum ContentType: string
{
    case JSON = 'application/json';
    case XML = 'application/xml';
    case HTML = 'text/html';
    case FORM_URLENCODED = 'application/x-www-form-urlencoded';
    case TEXT = 'text/plain';
    case MULTIPART_FORM_DATA = 'multipart/form-data';
    case OCTET_STREAM = 'application/octet-stream';
    case ANY = '*/*';

    /**
     * Check if the content type is JSON
     *
     * @return bool
     */
    public function isJson(): bool
    {
        return $this === self::JSON;
    }

    /**
     * Check if the content type is XML
     *
     * @return bool
     */
    public function isXml(): bool
    {
        return $this === self::XML;
    }

    /**
     * Check if the content type is HTML
     *
     * @return bool
     */
    public function isHtml(): bool
    {
        return $this === self::HTML;
    }

    /**
     * Check if the content type is Form URL Encoded
     *
     * @return bool
     */
    public function isFormUrlEncoded(): bool
    {
        return $this === self::FORM_URLENCODED;
    }

    /**
     * Check if the content type is Text
     *
     * @return bool
     */
    public function isText(): bool
    {
        return $this === self::TEXT;
    }

    /**
     * Check if the content type is Multipart Form Data
     *
     * @return bool
     */
    public function isMultipartFormData(): bool
    {
        return $this === self::MULTIPART_FORM_DATA;
    }

    /**
     * Check if the content type is Octet Stream
     *
     * @return bool
     */
    public function isOctetStream(): bool
    {
        return $this === self::OCTET_STREAM;
    }

    /**
     * Check if the content type is Any
     *
     * @return bool
     */
    public function isAny(): bool
    {
        return $this === self::ANY;
    }

    /**
     * Get the content type as a string
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Get the content type from a string
     *
     * @param string $value
     *
     * @throws InvalidArgumentException if the content type is invalid
     *
     * @return self
     */
    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'application/json' => self::JSON,
            'application/xml' => self::XML,
            'text/html' => self::HTML,
            'application/x-www-form-urlencoded' => self::FORM_URLENCODED,
            'text/plain' => self::TEXT,
            'multipart/form-data' => self::MULTIPART_FORM_DATA,
            'application/octet-stream' => self::OCTET_STREAM,
            '*' => self::ANY,
            default => throw new InvalidArgumentException('Invalid content type: ' . $value),
        };
    }

    /**
     * Get all content types as an array
     *
     * @return array
     */
    public static function all(): array
    {
        return array_map(fn($type) => $type->value, self::cases());
    }

    /**
     * Check if the content type is valid
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
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Get the content type from a header
     *
     * @param string $header
     *
     * @return self|null
     */
    public static function fromHeader(string $header): ?self
    {
        $contentType = explode(';', $header)[0] ?? null;
        if ($contentType && self::isValid($contentType)) {
            return self::fromString($contentType);
        }
        return null;
    }

    /**
     * Get the content type from a file extension
     *
     * @param string $extension
     *
     * @return self|null
     */
    public static function fromFileExtension(string $extension): ?self
    {
        return match (strtolower($extension)) {
            'json' => self::JSON,
            'xml' => self::XML,
            'html', 'htm' => self::HTML,
            'txt' => self::TEXT,
            'form' => self::FORM_URLENCODED,
            'multipart' => self::MULTIPART_FORM_DATA,
            'bin', 'octet-stream' => self::OCTET_STREAM,
            default => null,
        };
    }

    /**
     * Get the content type from a file path
     *
     * @param string $filePath
     *
     * @return self|null
     */
    public static function fromFilePath(string $filePath): ?self
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }

    /**
     * Get the content type from a file name
     *
     * @param string $fileName
     *
     * @return self|null
     */
    public static function fromFileName(string $fileName): ?self
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }

    /**
     * Get the content type from a file name with extension
     *
     * @param string $fileNameWithExtension
     *
     * @return self|null
     */
    public static function fromFileNameWithExtension(string $fileNameWithExtension): ?self
    {
        $extension = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }

    /**
     * Get the content type from a file path with extension
     *
     * @param string $filePathWithExtension
     *
     * @return self|null
     */
    public static function fromFilePathWithExtension(string $filePathWithExtension): ?self
    {
        $extension = pathinfo($filePathWithExtension, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }

    /**
     * Get the content type from a file
     *
     * @param string $filePath
     *
     * @return self|null
     */
    public static function fromFile(string $filePath): ?self
    {
        if (!file_exists($filePath)) {
            return null;
        }
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }

    /**
     * Get the content type from a file name
     *
     * @param string $fileName
     *
     * @return self|null
     */
    public static function fromFileNameOnly(string $fileName): ?self
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }

    /**
     * Get the content type from a file name with extension
     *
     * @param string $fileNameWithExtension
     *
     * @return self|null
     */
    public static function fromFileNameWithExtensionOnly(string $fileNameWithExtension): ?self
    {
        $extension = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
        return self::fromFileExtension($extension);
    }
}
