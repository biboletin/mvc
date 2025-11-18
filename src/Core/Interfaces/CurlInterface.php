<?php

namespace Bibo\Mvc\Core\Interfaces;

use CurlHandle;
use RuntimeException;

/**
 * Interface CurlInterface
 *
 * This interface defines the core functionality for cURL operations in the application.
 * It provides methods for configuring, executing, and retrieving information from cURL requests.
 * The interface can be implemented for both single and multi-handle cURL operations.
 *
 * @see CurlExtendedInterface For extended cURL functionality
 * @see CurlMultiInterface For multi-handle cURL operations
 */
interface CurlInterface
{
    /**
     * Sets the URL for the cURL request.
     *
     * @param string $url The URL to which the request will be sent
     *
     * @return CurlInterface
     */
    public function setUrl(string $url): self;

    /**
     * Sets the HTTP method for the cURL request.
     *
     * Common methods include GET, POST, PUT, DELETE, PATCH, etc.
     * The implementation should handle the method appropriately by setting
     * the correct cURL options.
     *
     * @param string $method The HTTP method to use for the request
     *
     * @return CurlInterface
     */
    public function setMethod(string $method): self;

    /**
     * Sets the HTTP headers for the cURL request.
     *
     * @param array $headers An associative array of header names and values
     *
     * @return CurlInterface
     */
    public function setHeaders(array $headers): self;

    /**
     * Sets the body content for the cURL request.
     *
     * This is typically used for POST, PUT, and PATCH requests.
     * The body can be a JSON string, form data, or any other format
     * supported by the API being called.
     *
     * @param string $body The request body content
     *
     * @return CurlInterface
     */
    public function setBody(string $body): self;

    /**
     * Executes the cURL request and returns the response body.
     *
     * This method performs the actual HTTP request using the configured
     * URL, method, headers, and body.
     *
     * @return array The response body as a array
     *
     * @throws RuntimeException If the cURL request fails
     */
    public function execute(): array;

    /**
     * Gets the HTTP status code from the last executed request.
     *
     * @return int The HTTP status code (e.g., 200, 404, 500)
     */
    public function getStatusCode(): int;

    /**
     * Gets the response headers from the last executed request.
     *
     * @return array An associative array of response headers
     */
    public function getResponseHeaders(): array;

    /**
     * Gets the underlying cURL handle.
     *
     * This can be used for advanced operations or when direct access
     * to the cURL resource is needed.
     *
     * @return mixed The cURL handle resource
     */
    public function getHandle(): CurlHandle;

    /**
     * Closes the cURL handle and releases resources.
     *
     * This method should be called when the cURL handle is no longer needed
     * to prevent resource leaks.
     *
     * @return void
     */
    public function close(): void;
}
