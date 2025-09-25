<?php

namespace Bibo\Mvc\Core\Router;

use Bibo\Mvc\Core\Enums\HttpMethod;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\MethodNotAllowedException;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Bibo\Mvc\Core\Interfaces\RouterInterface;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Resolver\ArgumentResolver;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Response\RedirectResponse;
use Exception;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use RuntimeException;
use function array_find;

/**
 * BaseRouter class that handle route definitions
 */
class BaseRouter implements RouterInterface
{
    /**
     * Route container
     *
     * @var array
     */
    private array $routes = [];
    /**
     * Middleware container
     *
     * @var array
     */
    private array $middleware = [];
    /**
     * Current route group
     *
     * @var string
     */
    private string $currentRouteGroup = '';

    /**
     * Route name
     *
     * @var string|null
     */
    private ?string $name = null;
    /**
     * Container
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Route strategy
     *
     * @var CachedRegexMatchStrategy|RouteMatchingStrategyInterface
     */
    private RouteMatchingStrategyInterface|CachedRegexMatchStrategy $strategy;

    /**
     * Middleware dispatcher
     *
     * @var MiddlewareDispatcher|mixed
     */
    private MiddlewareDispatcher $middlewareDispatcher;

    /**
     * Constructor
     *
     * @throws ContainerExceptionInterface
     */
    public function __construct(ContainerInterface $container, ?RouteMatchingStrategyInterface $strategy = null)
    {
        $this->strategy = $strategy;
        $this->container = $container;
        $this->middlewareDispatcher = $container->get(MiddlewareDispatcher::class);
    }


    /**
     * Add a route to the router.
     *
     * @param string $method HTTP method
     * @param string $route Route path
     * @param callable|array $handler Controller or callable
     * @param array|null $middleware Optional middleware stack
     * @param string|null $name Optional route name
     */
    private function add(
        string $method,
        string $route,
        callable|array $handler,
        ?array $middleware = null,
        ?string $name = null
    ): void {
        $fullRoute = ($this->currentRouteGroup
                ? '/' . trim($this->currentRouteGroup, '/')
                : '') . '/' . trim($route, '/');
        $fullRoute = preg_replace('#//+#', '/', $fullRoute) ?: '/';

        $upperMethod = strtoupper(trim($method));
        $this->routes[$upperMethod][] = [
            'method' => $upperMethod,
            'route' => $fullRoute,
            'handler' => $handler,
            'middleware' => $middleware ?? [],
            'name' => $name,
        ];
    }

    /**
     * Set GET routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function get(string $route, callable|array $callable): RouterInterface
    {
        $this->add(
            HttpMethod::fromString('get')->value,
            $route,
            $callable,
            $this->middleware,
            $this->name
        );
        return $this;
    }

    /**
     * Set POST routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function post(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('post')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set PUT routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function put(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('put')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set DELETE routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function delete(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('delete')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set PATCH routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function patch(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('patch')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set HEAD routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function head(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('head')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set OPTIONS routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function options(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('options')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set CONNECT routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function connect(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('connect')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set TRACE routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function trace(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('trace')->value, $route, $callable, $middleware);
        return $this;
    }

    /**
     * Set ANY routes
     *
     * @param string         $route
     * @param array|callable $callable
     * @param array          $middleware
     *
     * @return RouterInterface
     */
    public function any(string $route, callable|array $callable, array $middleware = []): RouterInterface
    {
        $this->add(HttpMethod::fromString('any')->value, $route, $callable, $middleware);
        return $this;
    }


    /**
     * Group routes under a common prefix.
     *
     * @param string   $prefix
     * @param callable $callback Receives $this router instance
     *
     * @return BaseRouter
     */
    public function group(string $prefix, callable $callback): BaseRouter
    {
        $previousGroup = $this->currentRouteGroup;

        $this->currentRouteGroup = rtrim($previousGroup . '/' . trim($prefix, '/'), '/');

        $callback($this);

        $this->currentRouteGroup = $previousGroup;

        return $this;
    }

    /**
     * Redirect route
     *
     * @param string $from
     * @param string $to
     * @param int    $status
     *
     * @return BaseRouter
     */
    public function redirect(string $from, string $to, int $status = HttpStatus::Found->value): BaseRouter
    {
        return $this->get($from, function () use ($to, $status) {
            return new RedirectResponse($to, $status);
        });
    }

    /**
     * Resource route
     *
     * @param string $prefix
     * @param string $controller
     *
     * @return BaseRouter
     */
    public function resource(string $prefix, string $controller): BaseRouter
    {
        $this->get($prefix, [$controller, 'index']);
        $this->get("$prefix/create", [$controller, 'create']);
        $this->post($prefix, [$controller, 'store']);
        $this->get("$prefix/{id}", [$controller, 'show']);
        $this->get("$prefix/{id}/edit", [$controller, 'edit']);
        $this->put("$prefix/{id}", [$controller, 'update']);
        $this->delete("$prefix/{id}", [$controller, 'destroy']);

        return $this;
    }

    /**
     * Dispatch a request to the matching route.
     *
     * @param BaseRequest $request
     *
     * @return ResponseInterface
     * @throws MethodNotAllowedException
     * @throws NotFoundException|ContainerExceptionInterface
     */
    public function dispatch(BaseRequest $request): ResponseInterface
    {
        $method = strtoupper($request->getMethod());
        $path   = $request->getUri()->getPath();

        $routesForMethod = $this->routes[$method] ?? [];
        $routesForAny    = $this->routes['ANY'] ?? [];
        $allRoutes       = array_merge($routesForMethod, $routesForAny);

        // Try to match with the current strategy
        $match = $this->strategy->match($method, $path, $allRoutes);

        if ($match !== null) {
            $middlewareStack = $this->resolveMiddlewareStack($match['middleware'] ?? []);
            $coreHandler = fn (ServerRequestInterface $request)
            => $this->handle($match['handler'], $match['params'] ?? []);

            return $this->middlewareDispatcher->dispatch($request, $coreHandler, $middlewareStack);
        }

        // If no route matched, check if path exists with different method
        $allowedMethods = [];
        foreach ($this->routes as $routeMethod => $methodRoutes) {
            if ($routeMethod === $method) {
                continue;
            }
            $possibleMatch = $this->strategy->match($routeMethod, $path, $methodRoutes);
            if ($possibleMatch !== null) {
                $allowedMethods[] = $routeMethod;
            }
        }

        if (!empty($allowedMethods)) {
            throw new MethodNotAllowedException("Method {$method} not allowed for {$path}");
        }

        throw new NotFoundException("Route {$path} not found");
    }

    /**
     * Handles route
     *
     * @throws Exception
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface
     */
    protected function handleRoute(array $route, array $params = []): ResponseInterface
    {
        $handler = $route['handler'];

        $coreHandler = function (ServerRequestInterface $request) use ($handler, $params) {
            if (is_callable($handler)) {
                return $this->handleCallable($handler, $params);
            }

            if (is_array($handler) && count($handler) === 2) {
                return $this->handleController($handler, $params);
            }

            throw new Exception('Invalid route handler');
        };

        // You must create or inject the current request here
        $request = $this->container->get(BaseRequest::class);

        return $this->container->get(MiddlewareDispatcher::class)->dispatch(
            $request,
            $coreHandler,
            $route['middleware'] ?? []
        );
    }

    /**
     * Handles callables and wraps responses properly
     *
     * @throws JsonException
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    protected function handleCallable(callable $handler, array $params = []): ResponseInterface
    {
        // Resolve arguments
        $resolver   = $this->container->get(ArgumentResolver::class);
        $arguments  = $resolver->resolve($handler, $params);

        $result = $handler(...$arguments);

        return $this->normalizeResponse($result);
    }

    /**
     * Handle controller action
     *
     * @throws Exception|ContainerExceptionInterface
     */
    protected function handleController(array $handler, array $params = []): ResponseInterface
    {
        [$controllerClass, $method] = $handler;

        // Create a controller (container-aware)
        $controller = new $controllerClass($this->container); // clean, no $this->container injection

        // Resolve arguments
        $resolver   = $this->container->get(ArgumentResolver::class);
        $arguments  = $resolver->resolve($handler, $params);

        $result = $controller->$method(...$arguments);

        // Normalize return value
        return $this->normalizeResponse($result);
    }

    /**
     * Flatten middleware aliases and groups into a stack.
     *
     * @param array $routeMiddleware
     *
     * @return array
     */
    private function resolveMiddlewareStack(array $routeMiddleware): array
    {
        $resolved = [];
        foreach ($routeMiddleware as $alias) {
            if (isset($this->middlewareGroups[$alias])) {
                foreach ($this->middlewareGroups[$alias] as $m) {
                    $resolved[] = $m;
                }
            } elseif (isset($this->routeMiddleware[$alias])) {
                $resolved[] = $this->routeMiddleware[$alias];
            } else {
                // FQCN or instance
                $resolved[] = $alias;
            }
        }
        return $resolved;
    }

    /**
     * Handle a route handler (callable or controller).
     *
     * @param array|callable $handler
     * @param array          $params
     *
     * @return ResponseInterface
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws ReflectionException
     */
    protected function handle(array|callable $handler, array $params = []): ResponseInterface
    {
        $resolver = $this->container->get(ArgumentResolver::class);
        $arguments = $resolver->resolve($handler, $params);

        if (is_callable($handler)) {
            $result = $handler(...$arguments);

            return $this->normalizeResponse($result);
        }

        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            $controller = new $controllerClass($this->container); // clean, no container
            $resolver = $this->container->get(ArgumentResolver::class);
            $arguments = $resolver->resolve($handler, $params);
            return $this->normalizeResponse($controller->$method(...$arguments));
        }

        throw new RuntimeException('Invalid route handler');
    }

    /**
     * @throws JsonException|NotFoundException
     */
    protected function normalizeResponse(mixed $result): ResponseInterface
    {
        if ($result instanceof ResponseInterface) {
            return $result;
        }

        if (is_string($result)) {
            return new HtmlResponse($result);
        }

        if (is_array($result)) {
            return new JsonResponse($result);
        }
        throw new NotFoundException(
            'Controller must return instance of ResponseInterface, string, or array. ' .
            (is_object($result) ? get_class($result) : gettype($result)) . ' given.',
            HttpStatus::NotFound->value
        );
    }

    /**
     * Add middleware to the last added route.
     *
     * @param  string|array $middleware
     * @return BaseRouter
     */
    public function middleware(string|array $middleware): BaseRouter
    {
        $lastMethod = array_key_last($this->routes);
        if ($lastMethod === null) {
            return $this;
        }

        $lastIndex = array_key_last($this->routes[$lastMethod]);
        if ($lastIndex === null) {
            return $this;
        }

        $this->routes[$lastMethod][$lastIndex]['middleware'] = is_array($middleware) ? $middleware : [$middleware];

        return $this;
    }

    /**
     * Returns route name
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Returns middleware
     *
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
    /**
     * Find route
     *
     * @param string $method
     * @param string $route
     *
     * @return array|null
     */
    public function find(string $method, string $route): ?array
    {
        return array_find(
            $this->routes,
            fn (array $router) => $router['method'] === strtoupper($method) && $router['route'] === $route
        );
    }

    /**
     * Set a route name
     *
     * @param string $name
     *
     * @return BaseRouter
     */
    public function name(string $name): BaseRouter
    {
        $lastMethod = array_key_last($this->routes);
        if ($lastMethod === null) {
            return $this;
        }

        $lastRouteIndex = array_key_last($this->routes[$lastMethod]);
        if ($lastRouteIndex === null) {
            return $this;
        }

        $this->routes[$lastMethod][$lastRouteIndex]['name'] = $name;

        return $this;
    }

    /**
     * Get routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Set the routes for the application
     *
     * @param array $routes The array of routes to be set
     *
     * @return void
     */
    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }
}
