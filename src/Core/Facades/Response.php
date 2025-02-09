<?php

namespace Bibo\Core\Facades;

use Bibo\Core\Response\BaseResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response
{
    private static ?ResponseInterface $instance = null;

    /**
     * Get the singleton instance of BaseResponse.
     *
     * @return BaseResponse
     */
    protected static function getInstance(): ResponseInterface
    {
        if (self::$instance === null) {
            self::$instance = new BaseResponse();
        }
        return self::$instance;
    }

    /**
     * Get protocol version.
     *
     * @return string
     */
    public static function getProtocolVersion(): string
    {
        return self::getInstance()->getProtocolVersion();
    }

    /**
     * With protocol version.
     *
     * @param string $version
     *
     * @return void
     */
    public static function withProtocolVersion(string $version): void
    {
        self::$instance = self::getInstance()->withProtocolVersion($version);
    }

    /**
     * Get headers.
     *
     * @return array
     */
    public static function getHeaders(): array
    {
        return self::getInstance()->getHeaders();
    }

    /**
     * Check if header exists.
     *
     * @param string $name
     *
     * @return bool
     */
    public static function hasHeader(string $name): bool
    {
        return self::getInstance()->hasHeader($name);
    }

    /**
     * Get a specific header.
     *
     * @param string $name
     *
     * @return array
     */
    public static function getHeader(string $name): array
    {
        return self::getInstance()->getHeader($name);
    }

    /**
     * Get header as a string line.
     *
     * @param string $name
     *
     * @return string
     */
    public static function getHeaderLine(string $name): string
    {
        return self::getInstance()->getHeaderLine($name);
    }

    /**
     * Set header.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public static function withHeader(string $name, $value): void
    {
        self::$instance = self::getInstance()->withHeader($name, $value);
    }

    /**
     * Add a new header without replacing existing values.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public static function withAddedHeader(string $name, $value): void
    {
        self::$instance = self::getInstance()->withAddedHeader($name, $value);
    }

    /**
     * Remove a header.
     *
     * @param string $name
     *
     * @return void
     */
    public static function withoutHeader(string $name): void
    {
        self::$instance = self::getInstance()->withoutHeader($name);
    }

    /**
     * Get response body.
     *
     * @return StreamInterface
     */
    public static function getBody(): StreamInterface
    {
        return self::getInstance()->getBody();
    }

    /**
     * Set response body.
     *
     * @param StreamInterface $body
     *
     * @return void
     */
    public static function withBody(StreamInterface $body): void
    {
        self::$instance = self::getInstance()->withBody($body);
    }

    /**
     * Get status code.
     *
     * @return int
     */
    public static function getStatusCode(): int
    {
        return self::getInstance()->getStatusCode();
    }

    /**
     * Set status code and optional reason phrase.
     *
     * @param int    $code
     * @param string $reasonPhrase
     *
     * @return void
     */
    public static function withStatus(int $code, string $reasonPhrase = ''): void
    {
        self::$instance = self::getInstance()->withStatus($code, $reasonPhrase);
    }

    /**
     * Get reason phrase.
     *
     * @return string
     */
    public static function getReasonPhrase(): string
    {
        return self::getInstance()->getReasonPhrase();
    }

    public static function send(): void
    {
        self::getInstance()->send();
    }

    public static function toJson(): string
    {
        $response = self::getInstance();
        return json_encode($response->getBody());
    }
}
