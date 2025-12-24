<?php

namespace Bibo\Mvc\Core\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;

/**
 * PSR-15 Middleware Dispatcher
 *
 * - Accepts middleware as:
 *   - MiddlewareInterface instances,
 *   - callable(Request, RequestHandler): Response (wrapped automatically),
 *   - class FQCN strings (resolved from container or new'ed),
 *   - alias strings that map to a class in routeMiddleware,
 *   - group names that expand to arrays of middleware.
 *
 * - Supports global middleware, groups and route aliases.
 */
final class MiddlewareDispatcher
{
    /**
     * Container instance.
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Global middleware stack.
     *
     * @var array<int, string|MiddlewareInterface|callable>
     */
    private array $global = [];

    /**
     * Middleware groups.
     *
     * @var array<string, array<int, string|MiddlewareInterface|callable>>
     */
    private array $groups = [];

    /**
     * Route-level middleware aliases.
     *
     * @var array<string, string|MiddlewareInterface|callable>
     */
    private array $routeMiddleware = [];

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Register global middleware (applied to all requests).
     *
     * @param array<int, string|MiddlewareInterface|callable> $middleware
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
     * @param array<int, string|MiddlewareInterface|callable> $middlewares
     *
     * @return void
     */
    public function defineGroup(string $group, array $middlewares): void
    {
        $this->groups[$group] = $middlewares;
    }

    /**
     * Register route middleware aliases.
     *
     * @param array<string, string|MiddlewareInterface|callable> $middleware
     *
     * @return void
     */
    public function registerRouteMiddleware(array $middleware): void
    {
        $this->routeMiddleware = array_merge($this->routeMiddleware, $middleware);
    }

    /**
     * Dispatch middleware stack for a given request.
     *
     * @param ServerRequestInterface $request
     * @param callable(ServerRequestInterface): ResponseInterface $coreHandler
     * @param array<int, string|MiddlewareInterface|callable> $routeMiddlewares Route-level middleware (aliases / classes / instances)
     *
     * @return ResponseInterface
     *
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function dispatch(
        ServerRequestInterface $request,
        callable $coreHandler,
        array $routeMiddlewares = []
    ): ResponseInterface {
        // Build combined stack: global first, then route-specific
        $stack = array_merge($this->global, $routeMiddlewares);

        // Resolve to array of MiddlewareInterface instances
        $resolved = $this->resolveMiddlewareStack($stack);

        // Build PSR-15 chain: wrap core handler
        $handler = new class ($coreHandler) implements RequestHandlerInterface {
            private $core;

            public function __construct(callable $core)
            {
                $this->core = $core;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return ($this->core)($request);
            }
        };

        // Build pipeline in reverse order
        foreach (array_reverse($resolved) as $middleware) {
            $handler = $this->wrapMiddleware($middleware, $handler);
        }

        return $handler->handle($request);
    }

    /**
     * Resolve an arbitrary stack (groups, aliases, instances, callables) into MiddlewareInterface[].
     *
     * - Recursively expands groups.
     * - Accepts objects (instances) directly.
     *
     * @param array<int, string|MiddlewareInterface|callable> $stack
     *
     * @return MiddlewareInterface[]
     *
     * @throws RuntimeException
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    private function resolveMiddlewareStack(array $stack): array
    {
        $resolved = [];

        foreach ($stack as $item) {
            // Already an instance: accept
            if ($item instanceof MiddlewareInterface) {
                $resolved[] = $item;
                continue;
            }

            // Callable: wrap later
            if (is_callable($item)) {
                $resolved[] = $this->wrapCallableAsMiddleware($item);
                continue;
            }

            // Strings: could be group, route alias, or FQCN
            if (is_string($item)) {
                // Group expand
                if (isset($this->groups[$item]) && is_array($this->groups[$item])) {
                    // recursion: expand group items and merge
                    $resolved = array_merge($resolved, $this->resolveMiddlewareStack($this->groups[$item]));
                    continue;
                }

                // Route alias
                if (isset($this->routeMiddleware[$item])) {
                    $aliasTarget = $this->routeMiddleware[$item];
                    // an alias target could be instanced, callable, or string
                    if ($aliasTarget instanceof MiddlewareInterface) {
                        $resolved[] = $aliasTarget;
                        continue;
                    }
                    if (is_callable($aliasTarget)) {
                        $resolved[] = $this->wrapCallableAsMiddleware($aliasTarget);
                        continue;
                    }
                    if (is_string($aliasTarget)) {
                        $resolved[] = $this->instantiateMiddleware($aliasTarget);
                        continue;
                    }

                    throw new RuntimeException('Invalid route middleware alias target for ' . $item . '"');
                }

                // Treat as FQCN
                $resolved[] = $this->instantiateMiddleware($item);
                continue;
            }

            // Anything else is invalid
            throw new RuntimeException('Invalid middleware type: ' . gettype($item));
        }

        return $resolved;
    }

    /**
     * Instantiate middleware by class name (resolve via container if present).
     *
     * If the container has the service, we use it. Otherwise we try to instantiate:
     *  - if the class constructor accepts a ContainerInterface as first arg -> pass container
     *  - else try no-arg constructor
     *
     * @param string $class
     *
     * @return MiddlewareInterface
     *
     * @throws RuntimeException on failure or if the result is not MiddlewareInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    private function instantiateMiddleware(string $class): MiddlewareInterface
    {
        // If container provides it, use container (allows full DI/autowiring)
        if ($this->container->has($class)) {
            $instance = $this->container->get($class);
        } else {
            // Not in container — try to instantiate safely
            if (!class_exists($class)) {
                throw new RuntimeException('Middleware class [' . $class . '] not found');
            }

            $ref = new ReflectionClass($class);

            if (!$ref->isInstantiable()) {
                throw new RuntimeException('Middleware class [' . $class . '] is not instantiable');
            }

            $ctor = $ref->getConstructor();
            if ($ctor === null || $ctor->getNumberOfParameters() === 0) {
                $instance = $ref->newInstance();
            } else {
                // Try first to pass container if the first param expects it
                $params = $ctor->getParameters();
                $firstType = $params[0]->getType();

                if ($firstType instanceof ReflectionNamedType && !$firstType->isBuiltin() && is_a($firstType->getName(), ContainerInterface::class, true)) {
                    $instance = $ref->newInstance($this->container);
                } else {
                    // Fallback: try zero-arg (already failed) or try to instantiate without args -> error
                    // We avoid trying to autowire here to keep dispatcher simple; encourage registering in the container.
                    throw new RuntimeException(
                        'Cannot instantiate middleware [' . $class . '] — constructor requires parameters. Register it in the container.'
                    );
                }
            }
        }

        if (!$instance instanceof MiddlewareInterface) {
            throw new RuntimeException('Middleware [' . $class . '] must implement MiddlewareInterface');
        }

        return $instance;
    }

    /**
     * Wrap a callable into a MiddlewareInterface instance.
     * The callable signature should be: function(ServerRequestInterface $req, RequestHandlerInterface $handler): ResponseInterface
     *
     * @param callable $callable
     *
     * @return MiddlewareInterface
     */
    private function wrapCallableAsMiddleware(callable $callable): MiddlewareInterface
    {
        return new class ($callable) implements MiddlewareInterface
        {
            private $callable;

            public function __construct(callable $callable)
            {
                $this->callable = $callable;
            }

            public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
            {
                return ($this->callable)($request, $handler);
            }
        };
    }

    /**
     * Wrap a MiddlewareInterface into a RequestHandler which delegates to the middleware.
     *
     * @param MiddlewareInterface $middleware
     * @param RequestHandlerInterface $next
     *
     * @return RequestHandlerInterface
     */
    private function wrapMiddleware(MiddlewareInterface $middleware, RequestHandlerInterface $next): RequestHandlerInterface
    {
        return new class ($middleware, $next) implements RequestHandlerInterface
        {
            private MiddlewareInterface $middleware;
            private RequestHandlerInterface $next;

            public function __construct(MiddlewareInterface $middleware, RequestHandlerInterface $next)
            {
                $this->middleware = $middleware;
                $this->next = $next;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->middleware->process($request, $this->next);
            }
        };
    }

    // Helper getters (optional)

    /**
     * Retrieve the list of global middleware instances.
     *
     * @return array An array of middleware instances.
     */
    public function getGlobalMiddlewares(): array
    {
        return $this->global;
    }

    /**
     * Retrieve the list of groups.
     *
     * @return array The array of groups.
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Retrieves the middlewares associated with the route.
     *
     * @return array An array of route middlewares.
     */
    public function getRouteMiddlewares(): array
    {
        return $this->routeMiddleware;
    }
}
