<?php

namespace Biboletin\Mvc\Core\Http;

use Exception;
use Psr\Http\Message\StreamInterface;

trait BaseMessageTrait
{
    protected $version = '1.1';
    protected $headers = [];
    protected StreamInterface $body;

    public function getProtocolVersion()
    {
        return $this->version;
    }

    public function withProtocolVersion($version)
    {
        if ($this->version === $version) {
            return $this;
        }
        $clone = clone $this;
        $clone->version = $version;

        return $clone;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function hasHeader($name)
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name)
    {
        $header = strtolower($name);
        if (!$this->hasHeader($header)) {
            return [];
        }

        return $this->headers[$header];
    }

    public function getHeaderLine($name)
    {
        $values = $this->getHeader($name);

        return implode(', ', $values);
    }

    public function withHeader($name, $value)
    {
        if (!is_string($name)) {
            throw new Exception('Argument 1 must be a string!');
        }
        if (!is_string($name) && !is_array($value)) {
            throw new Exception('Argument 2 must be a string!');
        }
        $header = strtolower($name);
        if (is_string($value)) {
            $value = [$value];
        }
        $clone = clone $this;
        $clone->headers[$header] = array_merge($clone->headers, $value);

        return $clone;
    }

    public function withAddedHeader($name, $value)
    {
        if (!is_string($name)) {
            throw new Exception('Argument 1 must be a string');
        }

        if (!is_string($name) && !is_array($value)) {
            throw new Exception('Argument 2 must be a string');
        }
        $header = strtolower($name);
        if (is_string($value)) {
            $value = [$value];
        }
        $clone = clone $this;
        $clone->headers[$header] = array_merge($clone->headers, $value);

        return $clone;
    }

    public function withoutHeader($name)
    {
        $clone = clone $this;
        unset($clone->headers[$name]);

        return $clone;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $header = strtolower($name);
            $this->headers[$header] = $value;
            if (is_string($value)) {
                $this->headers[$header] = explode(',', $value);
            }
        }
    }

    public function setBody($body)
    {
        if (!($body instanceof StreamInterface)) {
            $body = new Stream($body);
        }
        $this->body = $body;
    }

    public function inHeader(string $name, string $value)
    {
        $values = $this->getHeader($name);

        return in_array($value, $values);
    }
}
