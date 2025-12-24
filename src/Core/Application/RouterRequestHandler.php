<?php

namespace Bibo\Mvc\Core\Application;

use Bibo\Mvc\Core\Exception\Custom\Http\MethodNotAllowedException;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Router\BaseRouter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;

final class RouterRequestHandler implements RequestHandlerInterface
{
    /**
     * Router
     *
     * @var BaseRouter
     */
    private BaseRouter $router;

    /**
     * RouterRequestHandler constructor.
     *
     * @param BaseRouter $router
     */
    public function __construct(BaseRouter $router)
    {
        $this->router = $router;
    }

    /**
     * Dispatch request to router
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws MethodNotAllowedException
     * @throws ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->router->dispatch($request);
    }
}
