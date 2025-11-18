<?php

declare(strict_types=1);

namespace Bibo\Mvc\Core\Wrapper;

use Bibo\Mvc\Core\Interfaces\CurlInterface;
use Bibo\Mvc\Core\Wrapper\Curl\CurlSingleWrapper;
use CurlHandle;
use RuntimeException;

/**
 * CurlWrapper
 *
 * A simple cURL wrapper implementing the CurlInterface.
 * This class provides methods to set up and execute cURL requests,
 * as well as retrieve response information such as status code and headers.
 */
class CurlWrapper implements CurlInterface
{
    /**
     * @var CurlInterface|null
     */
    protected ?CurlInterface $handle = null;

    /**
     * @var array
     */
    protected array $headers = [];

    /**
     * @var string
     */
    protected string $body = '';

    /**
     * @var array
     */
    protected array $responseHeaders = [];

    /**
     * Constructor
     * Initializes the cURL handle.
     *
     * @param CurlInterface $strategy The cURL strategy to use (single or multi)
     */
    public function __construct(CurlInterface $strategy)
    {
        $this->handle = $strategy;
    }

    /**
     * Sets the URL for the cURL request.
     *
     * @param string $url
     *
     * @return CurlWrapper
     */
    public function setUrl(string $url): self
    {
        $this->handle->setUrl($url);

        return $this;
    }

    /**
     * Sets the HTTP method for the cURL request.
     *
     * @param string $method
     *
     * @return CurlWrapper
     */
    public function setMethod(string $method): self
    {
        $this->handle->setMethod(strtoupper(trim($method)));
    }

    /**
     * Sets the HTTP headers for the cURL request.
     *
     * @param array $headers
     *
     * @return CurlWrapper
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        $this->handle->setHeaders($headers);

        return $this;
    }

    /**
     * Sets the body content for the cURL request.
     *
     * @param string $body
     *
     * @return CurlWrapper
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        $this->handle->setBody($body);

        return $this;
    }

    /**
     * Sets a single cURL option.
     *
     * @param int   $option
     * @param mixed $value
     *
     * @return $this
     */
    public function setOption(int $option, mixed $value): self
    {
        $this->handle->setOption($option, $value);

        return $this;
    }

    /**
     * Sets a progress callback for the cURL request.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function onProgress(callable $callback): self
    {
        if (method_exists($this->handle, 'onProgress')) {
            $this->handle->onProgress($callback);
        }

        return $this;
    }

    /**
     * Sets a chunk callback for the cURL request.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function onChunk(callable $callback): self
    {
        if (method_exists($this->handle, 'onChunk')) {
            $this->handle->onChunk($callback);
        }

        return $this;
    }

    /**
     * Executes the cURL request.
     *
     * @return string The response body
     */
    public function execute(): array
    {
        $this->responseHeaders = []; // Reset headers
        $response = $this->handle->execute();
        $this->body = $response['body'] ?? '';

        if ($this->body === false) {
            throw new RuntimeException('cURL error during execution');
        }

        return $response;
    }

    /**
     * Executes all cURL requests in a multi-handle.
     *
     * @return array|null
     */
    public function executeAll(): ?array
    {
        if (method_exists($this->handle, 'executeAll')) {
            return $this->handle->executeAll();
        }

        return null;
    }

    /**
     * Gets the response body of the cURL request.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        if ($this->handle instanceof CurlSingleWrapper) {
            return $this->handle->getInfo(CURLINFO_HTTP_CODE);
        }

        return 0;
    }

    /**
     * Gets the response headers of the cURL request.
     *
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * Gets the response body of the cURL request.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Gets the response headers of the cURL request.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * Gets the response body of the cURL request.
     *
     * @return CurlHandle
     */
    public function getHandle(): CurlHandle
    {
        return $this->handle;
    }

    /**
     * @return void
     */
    public function close(): void
    {
        if (method_exists($this->handle, 'close')) {
            $this->handle->close();
        }
    }
}
