<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Response\JsonResponse;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Random\RandomException;

/**
 * CSRF Middleware
 */
class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Session key for CSRF token
     *
     * @var string
     */
    private string $sessionKey = '_csrf_token';
    /**
     * Field name for CSRF token in request
     *
     * @var string
     */
    private string $fieldName = '_csrf';

    /**
     * @throws RandomException
     * @throws JsonException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Start session if isn't started
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Create CSRF token if missing
        if (empty($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = bin2hex(random_bytes(32));
        }

        // Add CSRF token to request attributes (you can inject it into views)
        $request = $request->withAttribute('csrf_token', $_SESSION[$this->sessionKey]);

        // Only validate on unsafe methods (POST, PUT, DELETE, PATCH)
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'], true)) {
            $parsedBody = $request->getParsedBody();

            $tokenFromRequest = $parsedBody[$this->fieldName] ?? null;
            $tokenFromSession = $_SESSION[$this->sessionKey] ?? null;

            if (!$tokenFromRequest || !hash_equals($tokenFromSession, $tokenFromRequest)) {
                // CSRF token invalid
                return $this->forbiddenResponse($request);
            }
        }

        // Pass request to next middleware / handler
        return $handler->handle($request);
    }

    /**
     * Create a 403 Forbidden Response
     *
     * @throws JsonException
     */
    private function forbiddenResponse(ServerRequestInterface $request): ResponseInterface
    {
        if (str_contains($request->getHeaderLine('Accept'), 'application/json')) {
            return new JsonResponse(['error' => 'Invalid CSRF token'], 403);
        }

        return new HtmlResponse('<h1>403 Forbidden</h1><p>Invalid CSRF token.</p>', 403);
    }
}
