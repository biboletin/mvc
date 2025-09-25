<?php

namespace Bibo\Mvc\Core\Traits\Http;

trait ClientInfoHelper
{
    public function getClientIp(): ?string
    {
        $server = $this->getServerParams();

        // Check common proxy headers first
        $forwardedFor = $this->getHeaderLine('X-Forwarded-For');
        if (!empty($forwardedFor)) {
            // Return the first IP in the list
            return trim(explode(',', $forwardedFor)[0]);
        }

        $realIp = $this->getHeaderLine('X-Real-IP');
        if (!empty($realIp)) {
            return $realIp;
        }

        // Fallback to REMOTE_ADDR
        return $server['REMOTE_ADDR'] ?? null;
    }

    public function getIpChain(): array
    {
        $forwardedFor = $this->getHeaderLine('X-Forwarded-For');
        if (!empty($forwardedFor)) {
            return array_map('trim', explode(',', $forwardedFor));
        }
        $ip = $this->getClientIp();

        return $ip ? [$ip] : [];
    }

    public function getUserAgent(): ?string
    {
        $ua = $this->getHeaderLine('User-Agent');

        return $ua !== '' ? $ua : null;
    }

    public function getHost(): ?string
    {
        $host = $this->getHeaderLine('Host');
        if (!empty($host)) {
            return $host;
        }
        $server = $this->getServerParams();

        return $server['SERVER_NAME'] ?? null;
    }

    public function getPort(): ?int
    {
        $server = $this->getServerParams();

        return isset($server['SERVER_PORT']) ? (int) $server['SERVER_PORT'] : null;
    }

    public function getScheme(): string
    {
        $server = $this->getServerParams();
        if (!empty($server['HTTPS']) && strtolower($server['HTTPS']) !== 'off') {
            return 'https';
        }
        // Check forwarded protocol headers if behind a proxy
        $forwardedProto = $this->getHeaderLine('X-Forwarded-Proto');
        if (!empty($forwardedProto)) {
            return strtolower(explode(',', $forwardedProto)[0]);
        }

        return 'http';
    }

    public function isSecure(): bool
    {
        return $this->getScheme() === 'https';
    }

    public function getProtocolVersion(): string
    {
        $server = $this->getServerParams();
        if (!empty($server['SERVER_PROTOCOL'])) {
            return str_replace('HTTP/', '', $server['SERVER_PROTOCOL']);
        }

        return $this->getProtocolVersion() ?? '1.1';
    }
}
