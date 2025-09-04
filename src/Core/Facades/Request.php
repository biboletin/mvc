<?php

namespace Bibo\Mvc\Core\Facades;

use Bibo\Mvc\Core\Request\BaseRequest;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class Request
{
    /**
     * Get the singleton instance of BaseRequest
     *
     * @return BaseRequest
     */
    private static BaseRequest $instance;

    /**
     * Creates new instance
     *
     * @param BaseRequest $request
     *
     * @return void
     */
    public static function init(BaseRequest $request): void
    {
        self::$instance = $request;
    }

    /**
     * Get HTTP method
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return self::$instance->getMethod();
    }

    /**
     * Get URI
     *
     * @return UriInterface
     */
    public static function getUri(): UriInterface
    {
        return self::$instance->getUri();
    }

    /**
     * Get HTTP headers
     *
     * @return array
     */
    public static function getHeaders(): array
    {
        return self::$instance->getHeaders();
    }

    /**
     * Get HTTP body
     *
     * @return StreamInterface
     */
    public static function getBody(): StreamInterface
    {
        return self::$instance->getBody();
    }

    /**
     * Get $_SERVER params
     *
     * @return array
     */
    public static function getServerParams(): array
    {
        return self::$instance->getServerParams();
    }

    /**
     * Get $_COOKIE params
     *
     * @return array
     */
    public static function getCookieParams(): array
    {
        return self::$instance->getCookieParams();
    }

    /**
     * Get query params
     *
     * @return array
     */
    public static function getQueryParams(): array
    {
        return self::$instance->getQueryParams();
    }

    /**
     * Get uploaded files - $_FILES
     *
     * @return array
     */
    public static function getUploadedFiles(): array
    {
        return self::$instance->getUploadedFiles();
    }

    /**
     * Get parsed body - $_POST
     *
     * @return array
     */
    public static function getParsedBody(): array
    {
        return self::$instance->getParsedBody();
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public static function getAttributes(): array
    {
        return self::$instance->getAttributes();
    }

    /**
     * Get protocol version
     *
     * @return string
     */
    public static function getProtocolVersion(): string
    {
        return self::$instance->getProtocolVersion();
    }

    /**
     * Return an instance with the specified HTTP protocol version
     *
     * @param string $version HTTP protocol version
     *
     * @return BaseRequest
     */
    public static function withProtocolVersion(string $version): BaseRequest
    {
        return self::$instance->withProtocolVersion($version);
    }

    /**
     * With Method
     *
     * @param string $method
     *
     * @return BaseRequest
     */
    public static function withMethod(string $method): BaseRequest
    {
        return self::$instance->withMethod($method);
    }

    /**
     * With Uri
     *
     * @param UriInterface $uri
     *
     * @return BaseRequest
     */
    public static function withUri(UriInterface $uri): BaseRequest
    {
        return self::$instance->withUri($uri);
    }

    /**
     * With Header
     *
     * @param string $name
     * @param $value
     *
     * @return BaseRequest
     */
    public static function withHeader(string $name, $value): BaseRequest
    {
        return self::$instance->withHeader($name, $value);
    }

    /**
     * With Parsed Body
     *
     * @param $data
     *
     * @return BaseRequest
     */
    public static function withParsedBody($data): BaseRequest
    {
        return self::$instance->withParsedBody($data);
    }

    /**
     * With Cookie Params
     *
     * @param array $cookies
     *
     * @return BaseRequest
     */
    public static function withCookieParams(array $cookies): BaseRequest
    {
        return self::$instance->withCookieParams($cookies);
    }

    /**
     * With Query Params
     *
     * @param array $query
     *
     * @return BaseRequest
     */
    public static function withQueryParams(array $query): BaseRequest
    {
        return self::$instance->withQueryParams($query);
    }

    /**
     * With Uploaded Files
     *
     * @param array $uploadedFiles
     *
     * @return BaseRequest
     */
    public static function withUploadedFiles(array $uploadedFiles): BaseRequest
    {
        return self::$instance->withUploadedFiles($uploadedFiles);
    }

    /**
     * With Attribute
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return BaseRequest
     */
    public static function withAttribute(string $name, $value): BaseRequest
    {
        return self::$instance->withAttribute($name, $value);
    }

    /**
     * Without Attribute
     *
     * @param string $name
     *
     * @return BaseRequest
     */
    public static function withoutAttribute(string $name): BaseRequest
    {
        return self::$instance->withoutAttribute($name);
    }
}
