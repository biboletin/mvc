<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\TooManyRequestsException;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Bibo\Mvc\Core\Session\SessionHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimitMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    private int $limit = 5;   // Max requests per window
    private int $window = 10; // Window in seconds
    private SessionHandler $session;

    public function __construct(ContainerInterface $container)
    {
        try {
            parent::__construct($container);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }

        $this->session = $container->get(SessionHandler::class);
    }

    /**
     * @throws TooManyRequestsException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Attach session to request if not already
        if (!$request->getAttribute('session')) {
            $request = $request->withAttribute('session', $this->session);
        }

        $ip = $request->getClientIp();
        $key = "rate_limit_{$ip}";
        $session = $request->getAttribute('session');

        // Read existing rate-limit data
        $data = $session->get($key);

        // Update data
        $data = $this->updateRateLimitData($data);

        // Save back into session
        $session->set($key, $data);

        // Handle request
        $response = $handler->handle($request);

        // Add rate-limit headers
        $response = $this->addRateLimitHeaders($response, $data);

        // Explicitly save session at the end
        $session->writeClose();

        return $response;
    }

    /**
     * @throws TooManyRequestsException
     */
    private function updateRateLimitData(?array $data): array
    {
        $now = time();

        if ($data === null || ($now - $data['start']) >= $this->window) {
            // start a new window
            $data = ['count' => 1, 'start' => $now];
        } else {
            // increment count in the current window
            $data['count'] += 1;
            if ($data['count'] > $this->limit) {
                throw new TooManyRequestsException('Rate limit exceeded', HttpStatus::TooManyRequests->value);
            }
        }

        return $data;
    }

    private function addRateLimitHeaders(ResponseInterface $response, array $data): ResponseInterface
    {
        $remaining = max(0, $this->limit - $data['count']);
        $reset = ($data['start'] + $this->window) - time();

        return $response
            ->withHeader('X-RateLimit-Limit', (string) $this->limit)
            ->withHeader('X-RateLimit-Remaining', (string) $remaining)
            ->withHeader('X-RateLimit-Reset', (string) max(0, $reset));
    }

    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        $this->next = $middleware;
        return $this;
    }

    public function getNext(): ?MiddlewareInterface
    {
        return $this->next;
    }

    public function handle(): void
    {
    }

    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    public function clearNext(): void
    {
        $this->next = null;
    }

    public function toArray(): array
    {
        return [
            'class'   => static::class,
            'limit'   => $this->limit,
            'window'  => $this->window,
            'hasNext' => $this->hasNext(),
        ];
    }
}
