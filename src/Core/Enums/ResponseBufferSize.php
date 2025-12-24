<?php

namespace Bibo\Mvc\Core\Enums;

use Psr\Http\Message\ResponseInterface;

/**
 * Enum representing different buffer sizes for HTTP responses.
 *
 * This enum defines various buffer sizes that can be used when sending
 * HTTP responses. The sizes are defined in bytes and can be used to
 * optimize performance based on the expected response size.
 */
enum ResponseBufferSize: int
{
    /*
     * Default buffer size of 8KB.
     * This is the standard buffer size used for most responses.
     * It is suitable for typical web applications and provides a good balance
     * between performance and memory usage.
     * This size is often used for general-purpose responses
     * and is the default value for many web servers.
     * It is a common choice for web applications that do not require
     * large data transfers.
     */

    case DEFAULT = 8192;

    /*
     * Smaller buffer size of 4KB.
     * This can be used for smaller responses to reduce memory usage.
     * It is particularly useful for applications that handle
     * small data transfers or when memory efficiency is a priority.
     * This size is often used in scenarios where the response data
     * is relatively small, such as in APIs or lightweight web applications.
     * It helps to minimize memory overhead while still allowing
     * efficient data transfer.
     */

    case SMALL = 4096;

    /*
     * Medium buffer size of 16KB.
     * This is suitable for moderate-sized responses.
     * It provides a balance between performance and memory usage
     * for applications that handle larger data transfers.
     * This size is often used in scenarios where the response data
     * is larger than typical web pages but not excessively large.
     * It is a good choice for applications that require
     * efficient data transfer without excessive memory consumption.
     * It is commonly used in web applications that need to handle
     * moderate amounts of data, such as JSON APIs or file downloads.
     */

    case LARGE = 16384;

    /*
     * Extra large buffer size of 32KB.
     * This is used for very large responses, such as file downloads or large data sets.
     * It is suitable for applications that need to handle significant data transfers
     * without excessive memory overhead.
     * This size is often used in scenarios where the response data
     * is significantly larger than typical web pages or APIs.
     * It helps to ensure efficient data transfer while minimizing memory usage.
     */

    case EXTRA_LARGE = 32768;

    /*
     * Custom buffer size of 64KB.
     * This can be used for specialized applications that require
     * larger buffer sizes for optimal performance.
     * It is suitable for applications that handle very large data transfers
     * or require high throughput.
     * This size is often used in scenarios where the response data
     * is exceptionally large, such as in media streaming or large file transfers.
     * It helps to ensure efficient data transfer while minimizing memory overhead.
     */

    case CUSTOM = 65536;

    /**
     * Returns the buffer size in bytes.
     *
     * This method returns the value of the enum case as an integer,
     * representing the buffer size in bytes.
     *
     * @return int The buffer size in bytes.
     */
    public function toBytes(): int
    {
        return $this->value;
    }

    /**
     * Returns the appropriate buffer size based on the content type.
     *
     * This method determines the buffer size to use based on the provided
     * content type. It uses a match expression to select the appropriate
     * enum case based on common content types.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseBufferSize The appropriate buffer size for the content type.
     */
    public function byContentType(ResponseInterface $response): ResponseBufferSize
    {
        // Normalize the content type to lowercase for case-insensitive comparison
        $contentType = strtolower($response->getHeader('Content-Type')[0]);
        // Use a match expression to determine the appropriate buffer size
        $contentLength = (int) $response->getHeaderLine('Content-Length');

        if (!$contentLength && $response->getBody()->getSize() !== null) {
            $contentLength = $response->getBody()->getSize();
        }

        if (str_starts_with($contentType, 'image/')) {
            return match (true) {
                // Tiny icons, thumbnails
                $contentLength <= 4096 => self::SMALL,
                // Medium-size assets
                $contentLength <= 16384 => self::DEFAULT,
                // Large photos
                $contentLength <= 32768 => self::LARGE,
                // High-resolution images
                $contentLength <= 65536 => self::EXTRA_LARGE,
                // Huge uploads/downloads
                default => self::CUSTOM,
            };
        }

        return match (true) {
            str_contains($contentType, 'json'),
            str_contains($contentType, 'xml') => self::LARGE,
            str_contains($contentType, 'application/octet-stream'),
            str_contains($contentType, 'application/pdf'),
            str_contains($contentType, 'video/') => self::CUSTOM,
            default => self::DEFAULT,
        };
    }

    /**
     * Returns the appropriate buffer size based on the body size.
     *
     * This method determines the buffer size to use based on the provided
     * body size. It uses a match expression to select the appropriate
     * enum case based on common body sizes.
     *
     * @param ResponseInterface $response
     *
     * @return int The appropriate buffer size for the body size.
     */
    public function determine(ResponseInterface $response): int
    {
        $bodySize = $response->getBody()->getSize();

        if ($bodySize !== null) {
            return self::byBodySize($bodySize)->toBytes();
        }

        return self::byContentType($response)->toBytes();
    }

    /**
     * Returns the buffer size based on the body size.
     *
     * This method uses a match expression to determine the appropriate
     * buffer size based on the provided body size in bytes.
     * It categorizes the size into predefined ranges and returns
     * the corresponding enum case.
     *
     * @param int $size The body size in bytes.
     *
     * @return ResponseBufferSize The appropriate buffer size for the given size.
     */
    public function byBodySize(int $size): ResponseBufferSize
    {
        return match (true) {
            $size <= 4 * 1024 => self::SMALL,
            $size <= 16 * 1024 => self::DEFAULT,
            $size <= 64 * 1024 => self::LARGE,
            $size <= 256 * 1024 => self::EXTRA_LARGE,
            default => self::CUSTOM,
        };
    }

    /**
     * Returns the buffer size as a string.
     *
     * This method returns the value of the enum case as a string,
     * which can be useful for logging or debugging purposes.
     *
     * @return string The buffer size as a string.
     */
    public function toString(): string
    {
        return (string) $this->value;
    }
}
