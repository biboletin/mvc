<?php

namespace Bibo\Mvc\Core\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * MiddlewareInterface is the interface that all middleware classes must implement.
 * It defines a single method, process, which takes a ServerRequestInterface and
 * a RequestHandlerInterface as parameters and returns a ResponseInterface.
 */
interface MiddlewareInterface
{
    /**
     * Process the request and return a response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}
