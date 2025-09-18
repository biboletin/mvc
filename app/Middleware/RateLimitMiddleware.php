<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * Limit
     *
     * @var int
     */
    private int $limit = 100;

    /**
     * Window
     *
     * @var int
     */
    private int $window = 60;

    // public function __construct(int $limit = 100, int $window = 60)
    // {
    //     $this->limit = $limit;
    //     $this->window = $window;
    // }

    /**
     * Process middleware
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $key = "rate_limit_{$ip}";

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'time' => time()];
        }

        $data = &$_SESSION[$key];
        if (time() - $data['time'] < $this->window) {
            if ($data['count'] >= $this->limit) {
                return new HtmlResponse('Rate limit exceeded', HttpStatus::TooManyRequests->value);
            }
            $data['count']++;
        } else {
            $data = ['count' => 1, 'time' => time()];
        }

        return $handler->handle($request);
    }

    /**
     * @inheritDoc
     */
    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        // TODO: Implement setNext() method.
    }

    /**
     * @inheritDoc
     */
    public function getNext(): ?MiddlewareInterface
    {
        // TODO: Implement getNext() method.
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
        // TODO: Implement handle() method.
    }

    /**
     * @inheritDoc
     */
    public function hasNext(): bool
    {
        // TODO: Implement hasNext() method.
    }

    /**
     * @inheritDoc
     */
    public function clearNext(): void
    {
        // TODO: Implement clearNext() method.
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }
}