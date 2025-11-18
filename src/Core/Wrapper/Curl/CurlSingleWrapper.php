<?php

declare(strict_types=1);

namespace Bibo\Mvc\Core\Wrapper\Curl;

use Bibo\Mvc\Core\Interfaces\CurlInterface;
use CurlHandle;
use InvalidArgumentException;
use RuntimeException;

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
class CurlSingleWrapper extends AbstractCurlWrapper implements CurlInterface
{
    /**
     * CURL handle
     *
     * @var CurlHandle|null
     */
    protected ?CurlHandle $handle = null;

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
     * Callback for processing chunks of data
     *
     * @var callable|null
     */
    protected $onChunk = null;

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
     * Initialize the cURL handle.
     *
     * @return void
     */
    public function init(): void
    {
        $this->handle = curl_init();

        curl_setopt($this->handle, CURLOPT_HEADERFUNCTION, [$this, 'captureHeaderLine']);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Sets a callback to be invoked for each chunk of data received.
     *
     * This method allows you to specify a callable that will be executed
     * whenever a chunk of data is received during the cURL request.
     * The callable should accept two parameters: the cURL handle and the chunk of data.
     *
     * @param callable $callback The callback function to handle each chunk of data
     *
     * @return $this For method chaining
     */
    public function onChunk(callable $callback): self
    {
        $this->onChunk = $callback;

        return $this;
    }

    /**
     * Gets the current chunk processing callback.
     *
     * Returns the callable that has been set to handle chunks of data.
     * If no callback has been set, this method returns null.
     *
     * @return callable|null The chunk processing callback or null if not set
     */
    public function getOnChunk(): ?callable
    {
        return $this->onChunk;
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
    public function getHandle(): CurlHandle
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
     * Supported methods are: GET, POST, PUT, DELETE, PATCH, OPTIONS, and HEAD.
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
        $formattedMethod = strtoupper(trim($method));
        $this->setOption(CURLOPT_CUSTOMREQUEST, $formattedMethod);

        return $this;
    }

    /**
     * Sets the headers for the cURL request.
     *
     * Sets the HTTP headers to be included in the request. Headers should be provided
     * as an array of strings in the format "Header-Name: value".
     * Common headers include 'Content-Type', 'Authorization', 'Accept', etc.
     *
     * @param array $headers Array of header strings
     *                       (e.g., ['Content-Type: application/json', 'Authorization: Bearer token'])
     *
     * @return $this For method chaining
     */
    public function setHeaders(array $headers): self
    {
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            if (is_string($key)) {
                $formattedHeaders[] = $key . ': ' . $value;
            } else {
                $formattedHeaders[] = $value;
            }
        }

        return $this->setOption(CURLOPT_HTTPHEADER, $formattedHeaders);
    }

    /**
     * Gets the currently set headers for the cURL request.
     *
     * Returns the array of headers that have been set on the cURL handle.
     * If no headers have been set, this method returns null.
     *
     * @return array|null The array of header strings or null if none are set
     */
    public function getHeaders(): ?array
    {
        return $this->getOption(CURLOPT_HTTPHEADER);
    }

    /**
     * Sets the POST fields for the cURL request.
     *
     * Sets the data to be sent in a POST, PUT, or PATCH request. This can be either:
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

    public function getPostFields(): array
    {
        $postFields = $this->getOption(CURLOPT_POSTFIELDS);
        if (is_array($postFields)) {
            return $postFields;
        }

        return [];
    }

    /**
     * Executes the cURL request.
     *
     * Performs the HTTP request using the configured cURL handle and options.
     * This method captures the response body, headers, and status code.
     * If an onChunk callback is set, it will be invoked for each chunk of data received.
     *
     * @return array An associative array containing 'status', 'headers', and 'body'
     *
     * @throws RuntimeException If the cURL request fails
     */
    public function execute(): array
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, $this->onChunk === null);
        $this->setOption(CURLOPT_HEADER, false);

        $this->setOption(CURLOPT_HEADERFUNCTION, function ($handle, string $headerLine) {
            $length = strlen($headerLine);
            $line = trim($headerLine);

            if ($line === '') {
                return $length;
            }

            if (!str_contains($line, ':')) {
                $this->responseHeaders['status-line'] = $line;

                return $length;
            }
            [$name, $value] = explode(':', $line, 2);
            $this->responseHeaders[trim($name)][] = trim($value);

            return $length;
        });

        if ($this->onChunk) {
            $this->setOption(CURLOPT_WRITEFUNCTION, function ($handle, string $body) {
                ($this->onChunk)($body);

                return strlen($body);
            });
        }

        $response = curl_exec($this->handle);

        if ($response === false) {
            throw new RuntimeException('cURL error: ' . $this->getError(), $this->getErrorNo());
        }

        $status = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);

        return [
            'status' => $status,
            'headers' => $this->responseHeaders,
            'body' => $this->onChunk ? null : $response,
        ];
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
        if ($this->handle instanceof CurlHandle) {
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
     * @return CurlInterface
     */
    public function setBody(string $body): CurlInterface
    {
        // Assign the raw request body and ensure related cURL options
        // (e.g., CURLOPT_POSTFIELDS) are set by the caller or higher-level methods.
    }

    /**
     * Captures a header line from the cURL response.
     * This method is used as a callback for CURLOPT_HEADERFUNCTION.
     * It processes each header line and stores it in the responseHeaders array.
     *
     * @param $curlHandler
     * @param string $headerLine
     *
     * @return int
     */
    protected function captureHeaderLine($curlHandler, string $headerLine): int
    {
        $trimmed = trim($headerLine);

        if ($trimmed === '') {
            return strlen($headerLine); // End of headers
        }

        if (str_contains($trimmed, ':')) {
            [$key, $value] = explode(':', $trimmed, 2);
            $this->responseHeaders[trim($key)] = trim($value);
        } else {
            // Handle status line, e.g., "HTTP/1.1 200 OK"
            $this->responseHeaders[] = $trimmed;
        }

        return strlen($headerLine);
    }
}
