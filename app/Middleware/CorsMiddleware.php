<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Enums\HttpMethod;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorsMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    private array $config;

    public function __construct(ContainerInterface $container)
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

        try {
            parent::__construct($container);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }
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

    /**
     * Set the next middleware in the chain.
     *
     * @param MiddlewareInterface $middleware The next middleware to set.
     *
     * @return MiddlewareInterface The current middleware instance for chaining.
     */
    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        // TODO: Implement setNext() method.
    }

    /**
     * Get the next middleware in the chain.
     *
     * @return MiddlewareInterface|null The next middleware, or null if there is no next middleware.
     */
    public function getNext(): ?MiddlewareInterface
    {
        // TODO: Implement getNext() method.
    }

    /**
     * Handle the middleware logic.
     *
     * This method should be called to execute the middleware's logic.
     */
    public function handle(): void
    {
        // TODO: Implement handle() method.
    }

    /**
     * Check if there is a next middleware in the chain.
     *
     * @return bool True if there is a next middleware, false otherwise.
     */
    public function hasNext(): bool
    {
        // TODO: Implement hasNext() method.
    }

    /**
     * Clear the next middleware in the chain.
     *
     * This method should remove the reference to the next middleware.
     */
    public function clearNext(): void
    {
        // TODO: Implement clearNext() method.
    }

    /**
     * Convert the middleware to an associative array.
     *
     * This method should return the properties of the middleware as an associative array.
     *
     * @return array The middleware properties as an associative array.
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}
