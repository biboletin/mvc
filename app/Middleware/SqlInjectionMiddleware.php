<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\BadRequestException;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * SqlInjectionMiddleware
 *
 * Detects and blocks SQL injection attempts in query parameters and request body.
 */
class SqlInjectionMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    /**
     * List of suspicious SQL patterns to check against
     *
     * @var string[]
     */
    private array $patterns = [
        '/(\b)(SELECT|INSERT|UPDATE|DELETE|DROP|UNION|ALTER|CREATE|REPLACE)(\b)/i',
        '/(--|#|\/\*|\*\/|;)/', // comment or statement terminators
        '/(\b)(OR|AND)(\b).*=/', // boolean logic injections
        '/\bEXEC\b/i',
        '/\bSLEEP\(/i',
    ];

    /**
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
     * Process request to detect SQL injection
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws BadRequestException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $inputs = array_merge(
            $request->getQueryParams() ?? [],
            $request->getParsedBody() ?? []
        );

        foreach ($inputs as $key => $value) {
            if ($this->detectInjection($value)) {
                throw new BadRequestException(
                    'Potential SQL Injection detected in input: ' . htmlspecialchars($key),
                    HttpStatus::BadRequest->value
                );
            }
        }

        return $handler->handle($request);
    }

    /**
     * Detect SQL injection patterns in a string or array
     *
     * @param mixed $value
     *
     * @return bool
     */
    private function detectInjection(mixed $value): bool
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                if ($this->detectInjection($v)) {
                    return true;
                }
            }
            return false;
        }

        if (!is_string($value)) {
            return false;
        }

        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
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
