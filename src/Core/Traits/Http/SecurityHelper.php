<?php

namespace Bibo\Mvc\Core\Traits\Http;

/**
 * Security and credential helpers for HTTP requests.
 *
 * This trait expects the consumer to provide:
 * - getHeaderLine(string $name): string
 * - getCookieParams(): array
 */
trait SecurityHelper
{
    /**
     * Extract a Bearer token from the Authorization header.
     *
     * Header format: "Authorization: Bearer <token>".
     *
     * @return string|null The token string if present, otherwise null.
     */
    public function getBearerToken(): ?string
    {
        $authHeader = $this->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Parse HTTP Basic authentication credentials.
     *
     * @return array{0:string,1:string}|null [username, password] pair or null if not present/invalid.
     */
    public function getBasicAuth(): ?array
    {
        $authHeader = $this->getHeaderLine('Authorization');
        if (stripos($authHeader, 'Basic ') === 0) {
            $encoded = substr($authHeader, 6);
            $decoded = base64_decode($encoded, true);
            if ($decoded !== false) {
                $parts = explode(':', $decoded, 2);
                if (count($parts) === 2) {
                    return [$parts[0], $parts[1]];
                }
            }
        }

        return null;
    }

    /**
     * Get a cookie value by name with an optional default.
     *
     * @param  string $name Cookie name.
     * @param  mixed  $default Default value if cookie is not present.
     * @return mixed The cookie value or default.
     */
    public function getCookie(string $name, $default = null): mixed
    {
        $cookies = $this->getCookieParams();

        return $cookies[$name] ?? $default;
    }

    /**
     * Determine whether a cookie exists.
     *
     * @param  string $name Cookie name.
     * @return bool True if present, false otherwise.
     */
    public function hasCookie(string $name): bool
    {
        $cookies = $this->getCookieParams();

        return array_key_exists($name, $cookies);
    }

    /**
     * Return all cookies as an associative array.
     *
     * @return array<string, mixed>
     */
    public function getAllCookies(): mixed
    {
        return $this->getCookieParams();
    }

    /**
     * Get session ID from a cookie (default 'PHPSESSID').
     *
     * @param  string $cookieName The cookie name that stores the session id.
     * @return string|null The session id or null if missing.
     */
    public function getSessionId(string $cookieName = 'PHPSESSID'): ?string
    {
        return $this->getCookie($cookieName, null);
    }
}
