<?php

namespace Bibo\Mvc\Core\Middleware;

use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

// use Psr\Http\Server\MiddlewareInterface;

/**
 * Class MiddlewareDispatcher
 *
 * Handles registration, resolution, and dispatching of PSR-15 middlewares.
 * Allows combining global middleware, route-specific middleware, and
 * middleware groups into a single execution stack for HTTP requests.
 */
class MiddlewareDispatcher
{
    /**
     * @var array<int, string|MiddlewareInterface> List of global middleware applied to every request
     */
    protected array $global = [];

    /**
     * @var array<string, array<int, string|MiddlewareInterface>> Middleware groups
     * Groups allow multiple middlewares to be applied under a single alias.
     */
    protected array $groups = [];

    /**
     * @var array<string, string|MiddlewareInterface> Route-specific middleware
     * Key is the alias, value is the middleware class or instance.
     */
    protected array $routeMiddleware = [];

    /**
     * @var ContainerInterface Dependency injection container used to resolve middleware instances
     */
    private ContainerInterface $container;

    /**
     * MiddlewareDispatcher constructor.
     *
     * @param ContainerInterface $container PSR-11 container for dependency injection
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Register an array of global middleware applied to all requests.
     *
     * @param array<int, string|MiddlewareInterface> $middleware Array of middleware class names or instances
     *
     * @return void
     */
    public function registerGlobal(array $middleware): void
    {
        $this->global = $middleware;
    }

    /**
     * Define a middleware group.
     * A group allows combining multiple middlewares under a single alias.
     *
     * @param string                                 $group       Name of the middleware group
     * @param array<int, string|MiddlewareInterface> $middlewares Array of middleware class names or instances
     *
     * @return void
     */
    public function defineGroup(string $group, array $middlewares): void
    {
        $this->groups[$group] = $middlewares;
    }

    /**
     * Register route-specific middleware.
     * Middleware can be referenced by its alias when defining a route.
     *
     * @param array<string, string|MiddlewareInterface> $middleware Key is alias, value is class or instance
     *
     * @return void
     */
    public function registerRouteMiddleware(array $middleware): void
    {
        $this->routeMiddleware = array_merge($this->routeMiddleware, $middleware);
    }

    /**
     * Dispatch the middleware stack for a given request.
     * This method merges global middleware and route-specific middleware,
     * wraps them around the core request handler, and executes them in order.
     *
     * @param ServerRequestInterface                 $request     The current HTTP request
     * @param callable                               $coreHandler Core request handler that produces the final response
     * @param array<int, string|MiddlewareInterface> $middlewares Middleware aliases or classes for this route
     *
     * @return ResponseInterface The response returned after all middleware has been processed
     *
     * @throws Exception If a middleware cannot be resolved or does not implement MiddlewareInterface
     * @throws ContainerExceptionInterface
     */
    public function dispatch(
        ServerRequestInterface $request,
        callable $coreHandler,
        array $middlewares = []
    ): ResponseInterface {
        // Merge global middleware with route-specific middleware
        $allMiddleware = array_merge($this->global, $middlewares);

        // Wrap a core handler in a basic RequestHandler
        $handler = new class ($coreHandler) implements RequestHandlerInterface {
            private $coreHandler;

            public function __construct(callable $coreHandler)
            {
                $this->coreHandler = $coreHandler;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                // Core handler produces the final response
                return ($this->coreHandler)($request);
            }
        };

        // Wrap each middleware around the previous handler in reverse order
        foreach (array_reverse($this->resolveMiddlewareStack($allMiddleware)) as $middleware) {
            $handler = new class ($middleware, $handler) implements RequestHandlerInterface {
                private MiddlewareInterface $middleware;
                private RequestHandlerInterface $nextHandler;

                public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $nextHandler)
                {
                    $this->middleware   = $middleware;
                    $this->nextHandler  = $nextHandler;
                }

                public function handle(ServerRequestInterface $request): ResponseInterface
                {
                    return $this->middleware->process($request, $this->nextHandler);
                }
            };
        }

        // Execute the full middleware stack
        return $handler->handle($request);
    }

    /**
     * Expand + resolve middleware stack.
     *
     * @param array $middlewares Aliases, class names, groups, or instances
     *
     * @return MiddlewareInterface[]
     * @throws Exception*@throws ContainerExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function resolveMiddlewareStack(array $middlewares): array
    {
        $resolved = [];

        foreach ($middlewares as $middleware) {
            if ($middleware instanceof MiddlewareInterface) {
                $resolved[] = $middleware;
                continue;
            }

            // Expand group
            if (isset($this->groups[$middleware])) {
                foreach ($this->groups[$middleware] as $groupMiddleware) {
                    $resolved = array_merge(
                        $resolved,
                        $this->resolveMiddlewareStack([$groupMiddleware]) // recursion
                    );
                }
                continue;
            }

            // Resolve alias
            if (isset($this->routeMiddleware[$middleware])) {
                $resolved[] = $this->resolveMiddleware($this->routeMiddleware[$middleware]);
                continue;
            }

            // Assume FQCN
            $resolved[] = $this->resolveMiddleware($middleware);
        }

        return $resolved;
    }

    /**
     * Instantiate middleware by class name (via container if possible).
     *
     * @throws Exception|ContainerExceptionInterface
     */
    protected function instantiateMiddleware(string $middlewareClass): MiddlewareInterface
    {
        if ($this->container->has($middlewareClass)) {
            $instance = $this->container->get($middlewareClass);
        } else {
            if (!class_exists($middlewareClass)) {
                throw new Exception("Middleware class {$middlewareClass} not found");
            }
            $instance = new $middlewareClass($this->container);
        }

        if (!$instance instanceof MiddlewareInterface) {
            throw new Exception("Middleware {$middlewareClass} must implement MiddlewareInterface");
        }

        return $instance;
    }

    /**
     * Resolve middleware by alias, group, class, or instance.
     *
     * @param string|MiddlewareInterface $middleware
     * @return MiddlewareInterface
     *
     * @throws Exception|ContainerExceptionInterface
     */
    public function resolveMiddleware(string|MiddlewareInterface $middleware): MiddlewareInterface
    {
        if ($middleware instanceof MiddlewareInterface) {
            return $middleware;
        }

        // Alias
        if (isset($this->routeMiddleware[$middleware])) {
            return $this->instantiateMiddleware($this->routeMiddleware[$middleware]);
        }

        // Group (resolve first middleware in group or expand logic later)
        if (isset($this->groups[$middleware])) {
            $group = $this->groups[$middleware];
            $middlewareClass = is_array($group) ? $group[0] : $group;
            return $this->instantiateMiddleware($middlewareClass);
        }

        // Fallback: assume fully qualified class
        return $this->instantiateMiddleware($middleware);
    }
}
