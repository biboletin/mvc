<?php

namespace Bibo\Core\Middleware;

use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * MiddlewareDispatcher class that handles middleware registration and dispatching
 * to the request handler.
 */
class MiddlewareDispatcher
{
    /**
     * Global middleware to be applied to all requests.
     *
     * @var array
     */
    protected array $global = [];
    /**
     * Middleware groups that can be applied to specific routes.
     *
     * @var array
     */
    protected array $groups = [];
    /**
     * Route middleware that can be applied to specific routes.
     *
     * @var array
     */
    protected array $routeMiddleware = [];

    /**
     * Container instance for dependency injection.
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Register global middleware.
     *
     * @param array $middleware
     *
     * @return void
     */
    public function registerGlobal(array $middleware): void
    {
        $this->global = $middleware;
    }

    /**
     * Define a middleware group.
     *
     * @param string $group
     * @param array  $middleware
     *
     * @return void
     */
    public function defineGroup(string $group, array $middleware): void
    {
        $this->groups[$group] = $middleware;
    }

    /**
     * Register route middleware.
     *
     * @param array $middleware
     *
     * @return void
     */
    public function registerRouteMiddleware(array $middleware): void
    {
        $this->routeMiddleware[key($middleware)] = $middleware;
    }

    /**
     * Dispatch the middleware stack
     *
     * @param ServerRequestInterface $request
     * @param callable                $coreHandler
     * @param array                   $middlewares
     *
     * @return ResponseInterface
     * @throws Exception
     */
    public function dispatch(
        ServerRequestInterface $request,
        callable $coreHandler,
        array $middlewares = []
    ): ResponseInterface {
        $handler = new class ($coreHandler) implements RequestHandlerInterface {
            private $coreHandler;

            public function __construct(callable $coreHandler)
            {
                $this->coreHandler = $coreHandler;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return ($this->coreHandler)();
            }
        };

        // Wrap the core handler inside middleware stack
        foreach (array_reverse($middlewares) as $middlewareName) {
            $middleware = $this->resolveMiddleware($middlewareName);

            $handler = new class ($middleware, $handler) implements RequestHandlerInterface {
                private MiddlewareInterface $middleware;
                private RequestHandlerInterface $nextHandler;

                public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $nextHandler)
                {
                    $this->middleware = $middleware;
                    $this->nextHandler = $nextHandler;
                }

                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return $this->middleware->process($request, $this->nextHandler);
                }
            };
        }

        return $handler->handle($request);
    }

    private function resolveMiddleware(string|MiddlewareInterface $middleware): MiddlewareInterface
    {
        if (is_string($middleware)) {
            return $this->container->get($middleware);
        }

        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        throw new Exception('Invalid middleware: must be string or MiddlewareInterface instance');
    }
}
