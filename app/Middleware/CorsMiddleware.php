<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Enums\HttpMethod;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware implements MiddlewareInterface
{
    private array $config;

    public function __construct()
    {
        // Default configuration
        $this->config = array_merge([
            'allow_origin' => '*',
            'allow_methods' => implode(', ', [
                HttpMethod::GET->value,
                HttpMethod::POST->value,
                HttpMethod::PUT->value,
                HttpMethod::DELETE->value,
                HttpMethod::OPTIONS->value,
            ]),
            'allow_headers' => 'Content-Type, Authorization',
            'allow_credentials' => 'true',
            'max_age' => 3600,
        ], []);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Handle preflight request early
        if ($request->getMethod() === 'OPTIONS') {
            return $this->buildPreflightResponse();
        }

        // Proceed with normal request handling
        $response = $handler->handle($request);

        return $this->withCorsHeaders($response);
    }

    private function buildPreflightResponse(): ResponseInterface
    {
        $response = new HtmlResponse('', HttpStatus::NoContent->value);
        return $this->withCorsHeaders($response);
    }

    private function withCorsHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', $this->config['allow_origin'])
            ->withHeader('Access-Control-Allow-Methods', $this->config['allow_methods'])
            ->withHeader('Access-Control-Allow-Headers', $this->config['allow_headers'])
            ->withHeader('Access-Control-Allow-Credentials', $this->config['allow_credentials'])
            ->withHeader('Access-Control-Max-Age', (string) $this->config['max_age']);
    }
}
