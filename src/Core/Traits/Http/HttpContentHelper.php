<?php

namespace Bibo\Mvc\Core\Traits\Http;

trait HttpContentHelper
{
    public function getJsonBody(bool $assoc = true): mixed
    {
        $raw = $this->getRawBody();
        if (empty($raw)) {
            return null;
        }

        $decoded = json_decode($raw, $assoc);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $decoded;
    }

    public function getFormBody(): mixed
    {
        $parsed = $this->getParsedBody();

        if (is_array($parsed)) {
            return $parsed;
        }

        return [];
    }

    public function getRawBody(): string
    {
        // ensure a pointer at start
        $this->getBody()->rewind();

        return (string) $this->getBody();
    }

    public function isJson(): bool
    {
        $contentType = $this->getHeaderLine('Content-Type');

        return stripos($contentType, 'application/json') !== false;
    }

    public function wantsJson(): bool
    {
        $accept = $this->getHeaderLine('Accept');
        return stripos($accept, 'application/json') !== false;
    }
}
