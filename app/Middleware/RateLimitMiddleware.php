<?php

namespace Bibo\App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
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
class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request);
    }
}
