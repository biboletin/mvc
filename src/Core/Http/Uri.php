<?php

namespace Biboletin\Mvc\Core\Http;

use Exception;
use Psr\Http\Message\UriInterface;

use function parse_url;

class Uri implements UriInterface
{
    private const SCHEME_PORTS = [
        'http' => 80,
        'https' => 443,
    ];
    private const ALLOWED_SCHEMES = [
        'http',
        'https',
    ];
    private $scheme;
    private $host;
    private $port;
    private $user;
    private $password;
    private $path;
    private $query;
    private $fragment;

    public function __construct($uri)
    {
        $parts = parse_url($uri);
        $this->scheme = $parts['scheme'];
        $this->host = strtolower($parts['host'] ?? 'localhost');
        $this->setPort($parts['port'] ?? null);
        $this->user = $parts['user'];
        $this->password = $parts['password'];
        $this->path = $parts['path'];
        $this->query = $parts['query'];
        $this->fragment = $parts['fragment'];
    }

    public function setPort(?int $port)
    {
        if (self::SCHEME_PORTS[$this->scheme] === $port) {
            $this->port = null;

            return;
        }
        $this->port = $port;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getAuthority()
    {
        $authority = $this->host;
        if ($this->getUserInfo() !== '') {
            $authority = $this->getUserInfo() . '@' . $this->host;
        }

        if ($this->getPort() !== null) {
            $authority .= ':' . $this->getPort();
        }

        return $authority;
    }

    public function getUserInfo()
    {
        $info = $this->user;
        if ($this->password !== null && $this->password !== '') {
            $info .= ':' . $this->password;
        }
        return $info;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getPath()
    {
        $path = trim($this->path, '/');

        return '/' . $path;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function withScheme($scheme)
    {
        if ($this->scheme === $scheme) {
            return $this;
        }

        if (!in_array($scheme, self::ALLOWED_SCHEMES)) {
            throw new Exception('Unsupported scheme');
        }
        $clone = clone $this;
        $this->scheme = $scheme;

        return $clone;
    }

    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;
        $clone->user = $user;
        $clone->password = $password;

        return $clone;
    }

    public function withHost($host)
    {
        if (!is_string($host)) {
            throw new Exception('Invalid host');
        }
        if (strtolower($this->host) === strtolower($host)) {
            return $this;
        }

        $clone = clone $this;
        $clone->host = strtolower($host);

        return $clone;
    }

    public function withPort($port)
    {
        if ($this->port === $port) {
            return $this;
        }
        $clone = clone $this;
        $clone->setPort($port);

        return $clone;
    }

    public function withPath($path)
    {
        if (!is_string($path)) {
            throw new Exception('Invalid path');
        }

        if ($path === $this->path) {
            return $this;
        }

        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    public function withQuery($query)
    {
        if (!is_string($query)) {
            throw new Exception('Invalid query');
        }

        if ($query === $this->query) {
            return $this;
        }

        $clone = clone $this;
        $clone->query = $query;

        return $clone;
    }

    public function withFragment($fragment)
    {
        if ($fragment === $this->fragment) {
            return $this;
        }

        $clone = clone $this;
        $clone->fragment = $fragment;

        return $clone;
    }

    public function __toString()
    {
        $query = '';
        if ($this->query !== '') {
            $query = '?' . $this->query;
        }
        $fragment = '';
        if ($this->fragment !== '') {
            $fragment = '#' . $this->fragment;
        }

        return sprintf(
            '%s://%s%s%s%s',
            $this->getScheme(),
            $this->getAuthority(),
            $this->getPath(),
            $query,
            $fragment
        );
    }
}
