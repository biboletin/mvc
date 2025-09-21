<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Exception\Custom\Http\UnauthorizedException;
use Bibo\Mvc\Core\Session\SessionHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws UnauthorizedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $this->container->get(SessionHandler::class);
        $session->start();

        if (empty($session->get('user'))) {
            throw new UnauthorizedException();
        }

        return $handler->handle($request);
    }
}
