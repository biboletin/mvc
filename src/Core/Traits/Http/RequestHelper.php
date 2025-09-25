<?php

namespace Bibo\Mvc\Core\Traits\Http;

/**
 * Helper trait that adds convenient HTTP request inspection methods.
 *
 * This trait expects the consuming class to provide the following methods:
 * - getMethod(): string — Returns the HTTP method of the current request.
 * - getHeaderLine(string $name): string — Returns the value of a header as a single string.
 *
 * Typical consumers are PSR-7-like Request implementations or lightweight wrappers
 * that expose method and header accessors.
 */
trait RequestHelper
{
    /**
     * Check if the request's HTTP method matches the given method name (case-insensitive).
     *
     * @param string $method The HTTP method to compare against (e.g., GET, POST).
     * @return bool True if it matches, false otherwise.
     */
    public function isMethod(string $method): bool
    {
        return strcasecmp($this->getMethod(), $method) === 0;
    }

    /**
     * Determine if the request uses the GET method.
     *
     * @return bool True when method is GET, false otherwise.
     */
    public function isGet(): bool
    {
        return $this->isMethod('GET');
    }

    /**
     * Determine if the request uses the POST method.
     *
     * @return bool True when method is POST, false otherwise.
     */
    public function isPost(): bool
    {
        return $this->isMethod('POST');
    }

    /**
     * Determine if the request uses the PUT method.
     *
     * @return bool True when method is PUT, false otherwise.
     */
    public function isPut(): bool
    {
        return $this->isMethod('PUT');
    }

    /**
     * Determine if the request uses the PATCH method.
     *
     * @return bool True when method is PATCH, false otherwise.
     */
    public function isPatch(): bool
    {
        return $this->isMethod('PATCH');
    }

    /**
     * Determine if the request uses the DELETE method.
     *
     * @return bool True when method is DELETE, false otherwise.
     */
    public function isDelete(): bool
    {
        return $this->isMethod('DELETE');
    }

    /**
     * Determine if the request was made via AJAX (XMLHttpRequest).
     *
     * Uses the X-Requested-With header set by most JS libraries and browsers
     * for AJAX calls. Comparison is case-insensitive.
     *
     * @return bool True if X-Requested-With equals XMLHttpRequest, false otherwise.
     */
    public function isAjax(): bool
    {
        $header = $this->getHeaderLine('X-Requested-With');

        return strcasecmp($header, 'XMLHttpRequest') === 0;
    }
}
