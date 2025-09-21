<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Exception\Custom\Http\ForbiddenException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RBACMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    private array $allowedRoles;

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
     * @throws ForbiddenException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();
        $userRole = $_SESSION['role'] ?? 'guest';

        if (!in_array($userRole, $this->allowedRoles, true)) {
            throw new ForbiddenException();
        }

        return $handler->handle($request);
    }
}
