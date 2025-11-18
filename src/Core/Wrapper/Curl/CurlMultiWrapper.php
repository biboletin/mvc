<?php

declare(strict_types=1);

namespace Bibo\Mvc\Core\Wrapper\Curl;

use Bibo\Mvc\Core\Exception\Custom\Http\MethodNotSupportedException;
use Bibo\Mvc\Core\Interfaces\CurlExtendedInterface;
use Bibo\Mvc\Core\Interfaces\CurlInterface;
use Bibo\Mvc\Core\Interfaces\CurlMultiInterface;
use CurlHandle;
use CurlMultiHandle;
use Exception;
use RuntimeException;

/**
 * CurlMultiWrapper
 *
 * A wrapper for handling multiple cURL sessions concurrently using curl_multi.
 */
class CurlMultiWrapper extends AbstractCurlWrapper implements CurlMultiInterface
{
    /**
     * @var mixed|CurlMultiHandle $multiHandle The cURL multi-handle resource
     */
    protected mixed $multiHandle;

    /**
     * @var CurlInterface[] $handlers Array of CurlInterface instances added to this multi-handle
     */
    protected array $handlers = [];

    /**
     * @var array<int, callable|null> $callbacks Array of callbacks for each handle
     */
    protected array $callbacks = [];

    /**
     * Constructor
     * Initializes the cURL multi-handle.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Add a CurlSingleWrapper request to the multi-handle with an optional chunk callback.
     *
     * @param CurlSingleWrapper $handler
     * @param callable|null     $onChunk Optional callback for handling chunks of data
     *
     * @return self
     */
    public function addRequest(CurlSingleWrapper $handler, ?callable $onChunk = null): self
    {
        $handle = $handler->getHandle();

        if ($onChunk) {
            curl_setopt($handle, CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($onChunk) {
                $onChunk($data, $ch);

                return $onChunk($data);
            });
        }

        $this->handlers[(int) $handle] = $handler;
        $this->callbacks[(int)$handle] = $onChunk;
        curl_multi_add_handle($this->multiHandle, $handle);

        return $this;
    }

    /**
     * Add a CurlInterface instance to the multi-handle.
     *
     * @param CurlInterface $handle The CurlInterface instance to add
     *
     * @throws RuntimeException if unable to add the handle
     */
    public function addHandle(CurlInterface $handle): void
    {
        $this->handlers[] = $handle;
        $ch = $handle->getHandle();

        if (curl_multi_add_handle($this->multiHandle, $ch) !== CURLM_OK) {
            throw new RuntimeException('Unable to add cURL handle to multi handle');
        }
    }

    /**
     * Execute all added cURL requests concurrently and return their responses.
     *
     * @return array An array of responses, each containing 'status', 'headers', 'body', and 'handle'
     *
     * @throws Exception if any cURL error occurs during execution
     */
    public function executeAll(): array
    {
        $active = null;
        $responses = [];

        do {
            $status = curl_multi_exec($this->multiHandle, $active);
            if ($status > CURLM_OK) {
                throw new Exception('cURL error during multi-exec: ' . curl_multi_strerror($status));
            }

            curl_multi_select($this->multiHandle);

            while ($info = curl_multi_info_read($this->multiHandle)) {
                $handle = $info['handle'];
                $wrapper = $this->handlers[(int) $handle] ?? null;
                $handleId = (int) $handle;

                if (!$wrapper) {
                    continue;
                }

                if ($info['result'] !== CURLE_OK) {
                    $error = curl_error($handle);

                    throw new Exception($error, $info['result']);
                }

                $response = [
                    'status' => curl_getinfo($handle, CURLINFO_HTTP_CODE),
                    'headers' => $wrapper->getResponseHeaders(),
                    'body' => null,
                    'handle' => $wrapper,
                ];

                if (!$this->callbacks[$handleId]) {
                    $response['body'] = curl_multi_getcontent($handle);
                }
                $responses[] = $response;
                curl_multi_remove_handle($this->multiHandle, $handle);
                unset($this->handlers[$handleId], $this->callbacks[$handleId]);
            }
        } while ($active);

        curl_multi_close($this->multiHandle);

        return $responses;
    }

    public function getAllResponses(): array
    {
        $responses = [];

        foreach ($this->handlers as $index => $handle) {
            $ch = $handle->getHandle();
            $body = curl_multi_getcontent($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $headers = $handle->getResponseHeaders();

            $responses[] = [
                'body' => $body,
                'status' => $status,
                'headers' => $headers,
                'handle' => $handle,
            ];
        }

        return $responses;
    }

    public function getResponseBodies(): array
    {
        $bodies = [];
        foreach ($this->handlers as $handle) {
            $bodies[] = curl_multi_getcontent($handle->getHandle());
        }
        return $bodies;
    }

    public function getStatusCodes(): array
    {
        $codes = [];
        foreach ($this->handlers as $handle) {
            $codes[] = curl_getinfo($handle->getHandle(), CURLINFO_HTTP_CODE);
        }
        return $codes;
    }

    public function getAllHeaders(): array
    {
        $headers = [];
        foreach ($this->handlers as $handle) {
            $headers[] = $handle->getResponseHeaders();
        }
        return $headers;
    }

    public function close(): void
    {
        foreach ($this->handlers as $handle) {
            curl_multi_remove_handle($this->multiHandle, $handle->getHandle());
            $handle->close();
        }

        curl_multi_close($this->multiHandle);
    }

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
     *
     * @throws MethodNotSupportedException
     */
    public function setMethod(string $method): CurlInterface
    {
        throw new MethodNotSupportedException(
            'setMethod() is not supported in CurlMultiWrapper. Use CurlSingleWrapper for individual requests.'
        );
    }

    /**
     * Sets the HTTP headers for the cURL request.
     *
     * @param array $headers An associative array of header names and values
     *
     * @return CurlInterface
     *
     * @throws MethodNotSupportedException
     */
    public function setHeaders(array $headers): CurlInterface
    {
        throw new MethodNotSupportedException(
            'setHeaders() is not supported in CurlMultiWrapper. Use CurlSingleWrapper for individual requests.'
        );
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
     *
     * @throws MethodNotSupportedException
     */
    public function setBody(string $body): CurlInterface
    {
        throw new MethodNotSupportedException(
            'setBody() is not supported in CurlMultiWrapper. Use CurlSingleWrapper for individual requests.'
        );
    }

    /**
     * Executes the cURL request and returns the response body.
     *
     * This method performs the actual HTTP request using the configured
     * URL, method, headers, and body.
     *
     * @return array An array containing the response body, status code, and headers
     *
     * @throws MethodNotSupportedException
     */
    public function execute(): array
    {
        throw new MethodNotSupportedException(
            'execute() is not supported in CurlMultiWrapper. Use executeAll to run multiple requests.'
        );
    }

    /**
     * Gets the HTTP status code from the last executed request.
     *
     * @return int The HTTP status code (e.g., 200, 404, 500)
     *
     * @throws MethodNotSupportedException
     */
    public function getStatusCode(): int
    {
        throw new MethodNotSupportedException(
            'getStatusCode() is not supported in CurlMultiWrapper. Use getAllResponses ' .
            'to get status codes of all requests.'
        );
    }

    /**
     * Gets the response headers from the last executed request.
     *
     * @return array An associative array of response headers
     *
     * @throws MethodNotSupportedException
     */
    public function getResponseHeaders(): array
    {
        throw new MethodNotSupportedException(
            'getResponseHeaders() is not supported in CurlMultiWrapper. Use getAllResponses ' .
            'to get headers of all requests.'
        );
    }

    /**
     * Remove a cURL handle from the multi-handle.
     *
     * This method removes a previously added cURL handle from the multi-handle.
     * It should be called when a handle is no longer needed or after it has completed execution.
     *
     * @param CurlInterface $handle The cURL handle to remove from the multi-handle
     *
     * @return void
     *
     * @see curl_multi_remove_handle() PHP's native function for removing handles from a multi-handle
     */
    public function removeHandle(CurlInterface $handle): void
    {
    }

    /**
     * Wait for activity on any curl-connection.
     *
     * This method blocks until there is activity on any of the connections or until
     * the timeout is reached. It's useful for implementing non-blocking cURL operations.
     *
     * @return int Number of descriptors selected or -1 on error
     *
     * @see curl_multi_select() PHP's native function for waiting on multi-handle activity
     */
    public function select(): int
    {
        // TODO: Implement select() method.
    }

    /**
     * Get the number of running handles.
     *
     * This method returns the number of cURL handles that are still executing requests.
     * It's useful for monitoring the progress of parallel requests.
     *
     * @return int The number of running handles
     *
     * @see curl_multi_exec() PHP's native function that updates the running handles count
     */
    public function getRunningHandlesCount(): int
    {
        // TODO: Implement getRunningHandlesCount() method.
    }

    /**
     * Get the cURL multi-handle.
     *
     * This method retrieves the underlying cURL multi-handle resource.
     * It can be used for advanced operations or debugging.
     *
     * @return CurlHandle The cURL multi-handle resource
     *
     * @see curl_multi_init() PHP's native function for initializing a multi-handle
     */
    public function getHandle(): CurlHandle
    {
        return $this->multiHandle;
    }

    /**
     * Reset the cURL handle to its initial state.
     *
     * @return void
     */
    public function reset(): void
    {
        // TODO: Implement reset() method.
    }

    /**
     * Set the cURL handle.
     *
     * @param mixed $handle
     *
     * @return self
     */
    public function setHandle(mixed $handle): \Bibo\Mvc\Core\Interfaces\CurlExtendedInterface
    {
        // TODO: Implement setHandle() method.
    }

    /**
     * Sets the URL for the cURL request.
     *
     * @param string $url The URL to which the request will be sent
     *
     * @return CurlInterface
     *
     * @throws MethodNotSupportedException
     */
    public function setUrl(string $url): CurlInterface
    {
        throw new MethodNotSupportedException(
            'setUrl() is not supported in CurlMultiWrapper. Use CurlSingleWrapper for individual requests.'
        );
    }

    /**
     * Get the response body from the cURL handle.
     *
     * @return string
     */
    public function getRawResponse(): string
    {
        // TODO: Implement getRawResponse() method.
    }

    /**
     * Initialize the cURL handle.
     *
     * @return void
     */
    public function init(): void
    {
        $this->multiHandle = curl_multi_init();
    }
}
