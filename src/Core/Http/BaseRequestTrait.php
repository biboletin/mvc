<?php

namespace Biboletin\Mvc\Core\Http;

use Exception;
use Psr\Http\Message\UriInterface;

trait BaseRequestTrait
{
    protected $validMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
        'HEAD',
        'OPTION',
    ];
    protected $requestTarget = '';
    protected $method = '';
    protected UriInterface $uri;

    public function setUri($uri)
    {
        if (is_string($uri)) {
            $uri = new Uri($uri);
        }
        $this->uri = $uri;
    }

    public function getRequestTarget()
    {
        return $this->requestTarget;
    }

    public function withRequestTarget($requestTarget)
    {
        if ($this->requestTarget === $requestTarget) {
            return $this;
        }
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        if ($this->method === $method) {
            return $this;
        }

        if (!in_array($method, $this->validMethods)) {
            throw new Exception('Only ' . implode(', ', $this->validMethods) . ' are acceptable!');
        }
        $clone = clone $this;
        $clone->method = strtolower($method);

        return $clone;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        $clone->uri = $uri;
        if ($preserveHost === true) {
            $clone->uri = $uri->withHost($this->uri->getHost());
        }

        return $clone;
    }
}
