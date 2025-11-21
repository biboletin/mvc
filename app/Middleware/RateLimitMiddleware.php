<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\TooManyRequestsException;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Bibo\Mvc\Core\Session\SessionManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class RateLimitMiddleware
 *
 * Implements a simple session-based rate-limiting mechanism.
 * Tracks per-IP request counts within a defined rolling window.
 *
 * Features:
 *  - Enforces requests-per-window rate limit.
 *  - Adds standard rate-limit response headers.
 *  - Uses SessionManager for persistent metadata and tracking.
 *  - Fully PSR-15 and PSR-7 compatible.
 *
 * @package Bibo\App\Middleware
 */
class RateLimitMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    /**
     * Maximum number of requests allowed within the time window.
     *
     * @var int
     */
    private int $limit = 3;

    /**
     * Time window (in seconds) within which requests are counted.
     *
     * @var int
     */
    private int $window = 10;

    /**
     * Session handler instance used for storing rate-limit data.
     *
     * @var SessionManager
     */
    private SessionManager $session;

    /**
     * RateLimitMiddleware constructor.
     *
     * Loads SessionManager from the container and attaches it for use.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        try {
            parent::__construct($container);
            $this->session = $container->get(SessionManager::class);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            // Container bootstrapping failure is silently ignored here,
            // but can alternatively be logged using your Logger service.
        }
    }

    /**
     * Applies rate limiting to the incoming request.
     *
     * Logic:
     *  1. Ensure session is attached to request.
     *  2. Determine the client IP.
     *  3. Load per-IP rate-limit metadata from the session.
     *  4. Update or reset the window & request count.
     *  5. Throw TooManyRequestsException if exceeding limit.
     *  6. Add rate-limit response headers for transparency.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     *
     * @throws TooManyRequestsException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Attach SessionManager to request if not present
        if (!$request->getAttribute('session')) {
            $request = $request->withAttribute('session', $this->session);
        }

        // Determine client IP (PSR-7 compliant)
        $ip = $request->getServerParams()['REMOTE_ADDR'] ?? '0.0.0.0';
        $key = 'rate_limit_' . $ip;

        $session = $request->getAttribute('session');

        // Retrieve existing rate-limit window data
        $data = $session->get($key);

        // Update or reset the window data
        $data = $this->updateRateLimitData($data);

        // Store updated stats
        $session->set($key, $data);

        // Update session metadata (last used timestamp, regeneration logic, etc.)
        if ($session instanceof SessionManager) {
            $session->touch();
        }

        // Continue to next middleware/controller
        $response = $handler->handle($request);

        // Attach rate-limit headers to response
        return $this->addRateLimitHeaders($response, $data);
    }

    /**
     * Updates request count, resets window if needed, and enforces rate limits.
     *
     * @param array|null $data Existing rate-limit record:
     *                         [
     *                             'count' => int,
     *                             'start' => int (timestamp)
     *                         ]
     *
     * @return array Updated rate-limit data array
     *
     * @throws TooManyRequestsException If request count exceeds configured limit.
     */
    private function updateRateLimitData(?array $data): array
    {
        $now = time();

        // Reset window if expired or no data exists
        if ($data === null || ($now - $data['start']) >= $this->window) {
            return [
                'count' => 1,
                'start' => $now
            ];
        }

        // Increment request count
        $data['count']++;

        // Enforce maximum request limit
        if ($data['count'] > $this->limit) {
            throw new TooManyRequestsException('Rate limit exceeded', HttpStatus::TooManyRequests->value);
        }

        return $data;
    }

    /**
     * Adds standard rate-limit headers to the response.
     *
     * Headers added:
     *  - X-RateLimit-Limit:     Maximum allowed requests per window.
     *  - X-RateLimit-Remaining: Number of remaining calls before blocking.
     *  - X-RateLimit-Reset:     Seconds until the window resets.
     *
     * @param ResponseInterface $response
     * @param array             $data Rate-limit data after update.
     *
     * @return ResponseInterface
     */
    private function addRateLimitHeaders(ResponseInterface $response, array $data): ResponseInterface
    {
        $remaining = max(0, $this->limit - $data['count']);
        $reset     = max(0, ($data['start'] + $this->window) - time());

        return $response
            ->withHeader('X-RateLimit-Limit', (string)$this->limit)
            ->withHeader('X-RateLimit-Remaining', (string)$remaining)
            ->withHeader('X-RateLimit-Reset', (string)$reset);
    }

    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        // TODO: Implement setNext() method.
    }

    public function getNext(): ?MiddlewareInterface
    {
        // TODO: Implement getNext() method.
    }

    public function handle(): void
    {
        // TODO: Implement handle() method.
    }

    public function hasNext(): bool
    {
        // TODO: Implement hasNext() method.
    }

    public function clearNext(): void
    {
        // TODO: Implement clearNext() method.
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}
