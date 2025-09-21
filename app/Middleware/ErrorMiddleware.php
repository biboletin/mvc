<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    /**
     *
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            parent::__construct($container);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws NotFoundException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            return $this->responseFactory->createFromException($e, $request);
        }
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
