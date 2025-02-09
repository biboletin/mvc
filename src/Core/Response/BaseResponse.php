<?php

namespace Bibo\Core\Response;

use Bibo\Core\Request\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Base response class
 */
class BaseResponse implements ResponseInterface
{
    /**
     * HTTP status code
     *
     * @var int
     */
    private int $statusCode;
    /**
     * Headers
     *
     * @var array
     */
    private array $headers = [];
    /**
     * Stream body
     *
     * @var StreamInterface|Stream
     */
    private StreamInterface $body;
    /**
     * Reason phrase
     *
     * @var string
     */
    private string $reasonPhrase;
    /**
     * HTTP protocol version
     *
     * @var string
     */
    private string $protocolVersion = '1.1';

    /**
     * Status phrases
     */
    private const array STATUS_PHRASES = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        302 => 'Found',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error'
    ];

    /**
     * Constructor
     *
     * @param int                  $statusCode
     * @param array                $headers
     * @param StreamInterface|null $body
     */
    public function __construct(int $statusCode = 200, array $headers = [], ?StreamInterface $body = null)
    {
        $this->statusCode = $statusCode;
        $this->headers = $this->normalizeHeaders($headers);
        $this->body = $body ?? new Stream(fopen('php://temp', 'r+'));
        $this->reasonPhrase = self::STATUS_PHRASES[$this->statusCode] ?? '';
    }

    /**
     * Get protocol version
     *
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    /**
     * With protocol version
     *
     * @param string $version
     *
     * @return $this
     */
    public function withProtocolVersion(string $version): static
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    /**
     * Get headers
     *
     * @return array|string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Has header
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return array_key_exists(strtolower($name), $this->headers);
    }

    /**
     * Get header
     *
     * @param string $name
     *
     * @return array|string[]
     */
    public function getHeader(string $name): array
    {
        return $this->headers[strtolower($name)] ?? [];
    }

    /**
     * Get header line
     *
     * @param string $name
     *
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    /**
     * With header
     *
     * @param string $name
     * @param $value
     *
     * @return $this
     */
    public function withHeader(string $name, $value): static
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)] = (array) $value;
        return $clone;
    }

    /**
     * With added header
     *
     * @param string $name
     * @param $value
     *
     * @return $this
     */
    public function withAddedHeader(string $name, $value): static
    {
        $clone = clone $this;
        $normalized = strtolower($name);
        $clone->headers[$normalized] = array_merge($this->headers[$normalized] ?? [], (array) $value);
        return $clone;
    }

    /**
     * Without header
     *
     * @param string $name
     *
     * @return $this
     */
    public function withoutHeader(string $name): static
    {
        $clone = clone $this;
        unset($clone->headers[strtolower($name)]);
        return $clone;
    }

    /**
     * Get body
     *
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    /**
     * Body
     *
     * @param StreamInterface $body
     *
     * @return $this
     */
    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * With status
     *
     * @param int    $code
     * @param string $reasonPhrase
     *
     * @return $this
     */
    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase ?: (self::STATUS_PHRASES[$code] ?? '');
        return $clone;
    }

    /**
     * Get reason phrase
     *
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * Normalize headers
     *
     * @param array $headers
     *
     * @return array
     */
    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            $normalized[strtolower($name)] = (array) $value;
        }
        return $normalized;
    }

    public function send(): void
    {
        http_response_code($this->getStatusCode());

        foreach ($this->getHeaders() ?? [] as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        $body = $this->getBody();
        if ($body instanceof StreamInterface) {
            $body->rewind();
            echo $body->getContents();
        }
    }
}
