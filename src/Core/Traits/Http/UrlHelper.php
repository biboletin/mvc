<?php

namespace Bibo\Mvc\Core\Traits\Http;

/**
 * URL-related convenience helpers for HTTP request objects.
 *
 * This trait expects the consumer to provide:
 * - getUri(): Psr\Http\Message\UriInterface
 * - getHeaderLine(string $name): string
 */
trait UrlHelper
{
    /**
     * Get the path component of the current request URI.
     *
     * @return string Path portion without scheme/host/query.
     */
    public function getPath(): mixed
    {
        return $this->getUri()->getPath();
    }

    /**
     * Get the full URL for the current request.
     *
     * @return string Absolute URL including scheme, host, path and query.
     */
    public function getFullUrl(): string
    {
        return (string) $this->getUri();
    }

    /**
     * Get the base URL (scheme://host[:port]) for the current request.
     *
     * @return string Base origin URL.
     */
    public function getBaseUrl(): string
    {
        $uri = $this->getUri();
        $scheme = $uri->getScheme();
        $host = $uri->getHost();
        $port = $uri->getPort();
        $base = $scheme . '://' . $host;
        if ($port && !in_array($port, [80, 443])) {
            $base .= ':' . $port;
        }
        return $base;
    }

    /**
     * Get the absolute URL without the query string.
     *
     * @return string URL without query parameters.
     */
    public function getUrlWithoutQuery(): string
    {
        $uri = $this->getUri();
        return $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
    }

    /**
     * Return the raw query string from the URI (without leading '?').
     *
     * @return string Query string or empty string if none present.
     */
    public function getQueryString(): mixed
    {
        return $this->getUri()->getQuery();
    }

    /**
     * Parse the Accept-Language header into a list of language tags.
     *
     * @return string[] Lowercased language tags in client preference order.
     */
    public function getLanguages(): mixed
    {
        $accept = $this->getHeaderLine('Accept-Language');
        if (!$accept) {
            return [];
        }

        return array_map(function ($l) {
            $parts = explode(';', $l);

            return strtolower(trim($parts[0]));
        }, explode(',', $accept));
    }

    /**
     * Return the best matching language from a list of available locales.
     *
     * Performs a prefix match (e.g., 'en' matches 'en-US').
     *
     * @param  string[] $available List of available language codes/locales.
     * @return string|null The preferred available language or null if none match.
     */
    public function getPreferredLanguage(array $available): mixed
    {
        $langs = $this->getLanguages();
        foreach ($langs as $lang) {
            foreach ($available as $avail) {
                if (stripos($lang, $avail) === 0) {
                    return $avail;
                }
            }
        }

        return null;
    }
}
