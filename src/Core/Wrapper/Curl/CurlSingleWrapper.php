<?php

namespace Bibo\Core\Wrapper\Curl;

use Bibo\Core\Interfaces\CurlExtendedInterface;
use CurlHandle;
use InvalidArgumentException;

/**
 * CurlSingleWrapper class for handling single cURL requests.
 *
 * This class provides a wrapper around PHP's cURL functions for making individual HTTP requests.
 * It implements the CurlExtendedInterface and extends AbstractCurlWrapper to provide a consistent
 * API for working with cURL in a more object-oriented way.
 *
 * Features:
 * - Setting and managing cURL options
 * - Executing HTTP requests with various methods (GET, POST, PUT, DELETE, etc.)
 * - Handling request headers and body data
 * - Retrieving response data, headers, and status codes
 * - Error handling and information retrieval
 */
class CurlSingleWrapper extends AbstractCurlWrapper implements CurlExtendedInterface
{
    /**
     * CURL handle
     *
     * @var CurlHandle
     */
    protected mixed $handle;

    /**
     * CURL options
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Raw response from cURL
     *
     * @var string
     */
    protected string $rawResponse = '';

    /**
     * Response headers
     *
     * @var array
     */
    protected array $responseHeaders = [];

    /**
     * CurlSingleWrapper constructor.
     *
     * Initializes a new CurlSingleWrapper instance by creating a new cURL handle.
     * This constructor calls the init() method to set up the cURL handle.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initializes the cURL handle.
     *
     * Creates a new cURL handle using curl_init() and assigns it to the instance.
     * This method can be called to reset the wrapper with a fresh cURL handle.
     *
     * @return void
     */
    public function init(): void
    {
        $this->handle = curl_init();
    }

    /**
     * Sets the cURL handle.
     *
     * Allows setting an existing cURL handle to be used by this wrapper.
     * This is useful when you want to reuse an existing handle or when
     * you need to use a handle that was created with specific options.
     *
     * @param mixed $handle A cURL handle resource or CurlHandle object
     *
     * @return $this For method chaining
     */
    public function setHandle(mixed $handle): static
    {
        $this->handle = $handle;
        return $this;
    }

    /**
     * Gets the cURL handle.
     *
     * Returns the internal cURL handle that can be used with native cURL functions.
     * This is useful when you need to perform operations not directly supported
     * by this wrapper.
     *
     * @return mixed The cURL handle resource or CurlHandle object
     */
    public function getHandle(): mixed
    {
        return $this->handle;
    }

    /**
     * Sets a cURL option.
     *
     * Sets a single cURL option on the handle and stores it in the options array.
     * This method wraps curl_setopt() to provide a more object-oriented interface.
     *
     * @param int   $option The CURLOPT_* option constant to set
     * @param mixed $value  The value to set for the option
     *
     * @return $this For method chaining
     *
     * @link https://www.php.net/manual/en/function.curl-setopt.php List of cURL options
     */
    public function setOption(int $option, mixed $value): static
    {
        $this->options[$option] = $value;
        curl_setopt($this->handle, $option, $value);

        return $this;
    }

    /**
     * Sets multiple cURL options at once.
     *
     * Allows setting multiple cURL options in a single call, which is more efficient
     * than calling setOption() multiple times. This method wraps curl_setopt_array()
     * and also stores the options in the internal options array.
     *
     * @param array $options Associative array of cURL options where keys are CURLOPT_* constants
     *                       and values are the option values
     *
     * @return $this For method chaining
     *
     * @link https://www.php.net/manual/en/function.curl-setopt-array.php
     */
    public function setOptArray(array $options): static
    {
        $this->options = array_replace($this->options, $options);
        curl_setopt_array($this->handle, $options);

        return $this;
    }

    /**
     * Sets the URL for the cURL request.
     *
     * Sets the URL to be fetched or posted to. This is the most important option for any cURL request.
     * The URL should be properly encoded according to RFC 3986.
     *
     * @param string $url The URL to request (e.g., 'https://example.com/api')
     *
     * @return CurlSingleWrapper For method chaining
     */
    public function setUrl(string $url): static
    {
        return $this->setOption(CURLOPT_URL, $url);
    }

    /**
     * Sets the HTTP method for the cURL request.
     *
     * Configures the cURL handle to use the specified HTTP method.
     * Supported methods are: GET, POST, PUT, DELETE, PATCH, and HEAD.
     * Each method sets the appropriate cURL options to ensure the request
     * is sent correctly.
     *
     * @param string $method The HTTP method to use (case-insensitive)
     *
     * @return $this For method chaining
     *
     * @throws InvalidArgumentException If an unsupported HTTP method is provided
     */
    public function setMethod(string $method): static
    {
        switch (strtolower($method)) {
            case 'get':
                $this->setOption(CURLOPT_HTTPGET, true);
                break;
            case 'post':
                $this->setOption(CURLOPT_POST, true);
                break;
            case 'put':
                $this->setOption(CURLOPT_PUT, true);
                break;
            case 'delete':
                $this->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            case 'patch':
                $this->setOption(CURLOPT_CUSTOMREQUEST, 'PATCH');
                break;
            case 'head':
                $this->setOption(CURLOPT_NOBODY, true);
                break;
            default:
                throw new InvalidArgumentException('Invalid HTTP method: ' . $method);
        }

        return $this;
    }

    /**
     * Sets the headers for the cURL request.
     *
     * Sets the HTTP headers to be included in the request. Headers should be provided
     * as an array of strings in the format "Header-Name: value".
     * Common headers include 'Content-Type', 'Authorization', 'Accept', etc.
     *
     * @param array $headers Array of header strings (e.g., ['Content-Type: application/json', 'Authorization: Bearer token'])
     *
     * @return $this For method chaining
     */
    public function setHeaders(array $headers): static
    {
        return $this->setOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     * Sets the POST fields for the cURL request.
     *
     * Sets the data to be sent in a POST, PUT or PATCH request. This can be either:
     * - A URL-encoded string (e.g., 'param1=value1&param2=value2')
     * - An associative array (e.g., ['param1' => 'value1', 'param2' => 'value2'])
     * - A JSON string for API requests
     * - File data for uploads
     *
     * @param mixed $fields The data to send in the request body
     *
     * @return $this For method chaining
     */
    public function setPostFields(mixed $fields): static
    {
        return $this->setOption(CURLOPT_POSTFIELDS, $fields);
    }

    /**
     * Executes the cURL request.
     *
     * Performs the cURL request with all the options that have been set.
     * This method ensures CURLOPT_RETURNTRANSFER is set to true so that the
     * response is returned as a string rather than output directly.
     * The response is stored internally and can be retrieved with getRawResponse().
     *
     * @return string The response from the server
     *
     * @throws \RuntimeException If the cURL request fails (check getError() for details)
     */
    public function exec(): string
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->rawResponse = curl_exec($this->handle);

        return $this->rawResponse;
    }

    /**
     * Returns the raw response from the cURL request.
     *
     * Returns the complete raw response from the last executed request.
     * This method does not execute a new request; it only returns the
     * stored response from the last exec() call.
     *
     * @return string The raw response from the server
     */
    public function getRawResponse(): string
    {
        return $this->rawResponse;
    }

    /**
     * Returns the response headers from the cURL request.
     *
     * Returns the headers from the last executed request as an associative array.
     * Note: This implementation currently returns an empty array as header parsing
     * is not fully implemented. To properly implement this, CURLOPT_HEADERFUNCTION
     * should be used to capture and parse headers during the request.
     *
     * @return array Associative array of headers (key => value)
     */
    public function getResponseHeaders(): array
    {
        // Optional: Implement header parsing from CURLOPT_HEADERFUNCTION
        return $this->responseHeaders;
    }

    /**
     * Returns information about the cURL transfer.
     *
     * Retrieves information about the last transfer. This is a wrapper around
     * curl_getinfo() and can be used to get details like HTTP status code,
     * content type, total time, etc.
     *
     * @param int $option One of the CURLINFO_* constants
     *
     * @return mixed The requested information
     *
     * @link https://www.php.net/manual/en/function.curl-getinfo.php List of CURLINFO options
     */
    public function getInfo(int $option): mixed
    {
        return curl_getinfo($this->handle, $option);
    }

    /**
     * Returns the last error number.
     *
     * @return string
     */
    public function getError(): string
    {
        return curl_error($this->handle);
    }

    /**
     * Returns the last error number.
     *
     * @return int
     */
    public function getErrorNo(): int
    {
        return curl_errno($this->handle);
    }

    /**
     * Closes the cURL handle.
     *
     * @return void
     */
    public function close(): void
    {
        if (is_resource($this->handle) || $this->handle instanceof CurlHandle) {
            curl_close($this->handle);
        }
    }

    /**
     * Resets the cURL handle to its initial state.
     *
     * @return void
     */
    public function reset(): void
    {
        curl_reset($this->handle);
        $this->options = [];
        $this->rawResponse = '';
        $this->responseHeaders = [];
    }

    /**
     * Returns the HTTP status code from the cURL request.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
    }

    /**
     * Returns the response body from the cURL request.
     *
     * @return string
     */
    public function getResponseBody(): string
    {
        return $this->rawResponse;
    }

    /**
     * Sets the body content for the cURL request.
     *
     * This is typically used for POST, PUT, and PATCH requests.
     * The body can be a JSON string, form data, or any other format
     * supported by the API being called.
     *
     * @param string $body The request body content
     *
     * @return void
     */
    public function setBody(string $body): void
    {
        // TODO: Implement setBody() method.
    }

    /**
     * Executes the cURL request and returns the response body.
     *
     * This method performs the actual HTTP request using the configured
     * URL, method, headers, and body.
     *
     * @return string The response body as a string
     * @throws \RuntimeException If the cURL request fails
     */
    public function execute(): string
    {
        // TODO: Implement execute() method.
}}
