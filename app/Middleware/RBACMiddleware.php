<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class RBACMiddleware implements MiddlewareInterface
{
    private array $allowedRoles;

    public function __construct(array $allowedRoles = [])
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();
        $userRole = $_SESSION['role'] ?? 'guest';

        if (!in_array($userRole, $this->allowedRoles, true)) {
            return new HtmlResponse('Forbidden', HttpStatus::Forbidden->value);
        }

        return $handler->handle($request);
    }
}
