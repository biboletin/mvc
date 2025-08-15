<?php

namespace Bibo\Mvc\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareRequestHandler implements RequestHandlerInterface
{
    private array $middleware;
    private $controller;

    public function __construct(array $middleware, callable $controller)
    {
        $this->middleware = $middleware;
        $this->controller = $controller;
    }

    /**
     * Handles a request and produces a response.
     * May call other collaborating code to generate the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middleware)) {
            return call_user_func($this->controller, $request);
        }
        $middleware = array_shift($this->middleware);

        return $middleware->process($request, $this);
    }
}
