<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();
        if (empty($_SESSION['user'])) {
            return new HtmlResponse('Unauthorized', HttpStatus::Unauthorized->value);
        }

        return $handler->handle($request);
    }
}
