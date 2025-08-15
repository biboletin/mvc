<?php

namespace Bibo\Mvc\Core\Wrapper;

use Bibo\Mvc\Core\Interfaces\CurlInterface;
use CurlHandle;
use RuntimeException;

/**
 *
 */
class CurlWrapper implements CurlInterface
{
    /**
     * @var mixed|CurlHandle|false
     */
    protected mixed $handle;

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
     *
     */
    public function __construct()
    {
        $this->handle = curl_init();
        if ($this->handle === false) {
            throw new RuntimeException('Unable to initialize cURL handle');
        }

        // Setup header capture
        curl_setopt($this->handle, CURLOPT_HEADERFUNCTION, [$this, 'captureHeaderLine']);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * @param string $url
     *
     * @return CurlWrapper
     */
    public function setUrl(string $url): self
    {
        curl_setopt($this->handle, CURLOPT_URL, $url);

        return $this;
    }

    /**
     * @param string $method
     *
     * @return CurlWrapper
     */
    public function setMethod(string $method): self
    {
        switch (strtoupper($method)) {
            case 'GET':
                curl_setopt($this->handle, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($this->handle, CURLOPT_POST, true);
                break;
            default:
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, strtoupper($method));
                break;
        }
    }

    /**
     * @param array $headers
     *
     * @return CurlWrapper
     */
    public function setHeaders(array $headers): self
    {
        $formatted = [];
        foreach ($headers as $key => $value) {
            $formatted[] = "{$key}: {$value}";
        }

        curl_setopt($this->handle, CURLOPT_HTTPHEADER, $formatted);

        return $this;
    }

    /**
     * @param string $body
     *
     * @return void
     */
    public function setBody(string $body): void
    {
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, $body);
    }

    /**
     * @return string
     */
    public function execute(): string
    {
        $this->responseHeaders = []; // Reset headers
        $this->body = curl_exec($this->handle);

        if ($this->body === false) {
            throw new RuntimeException('cURL error: ' . curl_error($this->handle));
        }

        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
    }

    /**
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * @return CurlHandle|false|mixed
     */
    public function getHandle(): mixed
    {
        return $this->handle;
    }

    /**
     * @return void
     */
    public function close(): void
    {
        curl_close($this->handle);
    }

    /**
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

    public function setOption(int $option, $value): bool
    {
        return curl_setopt($this->handle, $option, $value);
    }
}
