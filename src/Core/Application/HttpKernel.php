<?php

namespace Bibo\Mvc\Core\Application;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Error\Error;
use Bibo\Mvc\Core\Error\ErrorResponseFactory;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Bibo\Mvc\Core\Router\BaseRouter;
use Bibo\Mvc\Core\Router\RouteDispatcher;
use Bibo\Mvc\Core\Router\RouteMatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use Throwable;

/**
 * Class HttpKernel
 *
 * @package Bibo\Core\Base
 */
final class HttpKernel
{
    /**
     * Application instance
     *
     * @var App
     */
    private App $app;

    /**
     * HttpKernel constructor.
     */
    public function __construct(
        App $app
    ) {
        $this->app = $app;
    }

    /**
     * Handles a request and produces a response.
     * May call other collaborating code to generate the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $matcher = $this->app->container()->get(RouteMatcher::class);
            $dispatcher = $this->app->container()->get(RouteDispatcher::class);
            $middleware = $this->app->container()->get(MiddlewareDispatcher::class);

            $matchedRoute = $matcher->match($request);

            if (!$matchedRoute) {
                throw new NotFoundException(
                    'No route found for ' . $request->getMethod() . ' ' . $request->getUri()->getPath()
                );
            }

            return $middleware->dispatch(
                $request,
                fn (ServerRequestInterface $req): ResponseInterface => $dispatcher->dispatch($matchedRoute, $req),
                $matchedRoute->middleware()
            );
        } catch (Throwable $e) {
            return $this->handleException($e, $request);
        }
    }

    public function terminate(ServerRequestInterface $request, ResponseInterface $response): void
    {
        // Nothing to do here for now
    }

    /**
     * Handles an exception by returning an error response.
     *
     * @param Throwable $e
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws ContainerExceptionInterface If resolving the entry fails.
     * @throws NotFoundExceptionInterface If no entry is found for the identifier.
     */
    private function handleException(Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $container = $this->app->container();

        $handler = $container->get(Error::class);
        $handler->handleException($e);

        return $container
            ->get(ErrorResponseFactory::class)
            ->createResponse(
                $handler->normalize($e),
                $request,
                $request->getAttribute('status') ?? HttpStatus::InternalServerError->value
            );
    }
}
