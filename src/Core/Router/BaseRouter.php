<?php

namespace Bibo\Mvc\Core\Router;

use Bibo\Mvc\Core\Enums\HttpMethod;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\MethodNotAllowedException;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Bibo\Mvc\Core\Interfaces\RouterInterface;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Bibo\Mvc\Core\Resolver\ArgumentResolver;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Response\RedirectResponse;
use Bibo\Mvc\Core\Traits\ContainerAwareTrait;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use RuntimeException;
use Throwable;

/**
 * BaseRouter class that handle route definitions
 */
class BaseRouter implements RouterInterface
{
    /**
     * Current middleware stack for the group
     *
     * @var array
     */
    private array $currentGroupMiddleware = [];

    /**
     * Current route group
     *
     * @var string
     */
    private string $currentGroupPrefix = '';

    /**
     * Constructor
     *
     * @param RouteCollection $routes
     */
    public function __construct(
        private readonly RouteCollection $routes
    ) {
    }

    /**
     * Add a route to the router.
     *
     * @param string $method HTTP method
     * @param string $path
     * @param callable|array $handler Controller or callable
     * @param array|null $middleware Optional middleware stack
     * @param string|null $name Optional route name
     *
     * @return BaseRouter
     */
    private function add(
        string $method,
        string $path,
        callable|array $handler,
        ?array $middleware = null,
        ?string $name = null
    ): self {
        $fullPath = '/' . trim(
            $this->currentGroupPrefix . '/' . trim($path, '/'),
            '/'
        );

        $this->routes->add(
            $method,
            $fullPath ?: '/',
            $handler,
            $middleware,
            $name
        );

        return $this;
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
        return $this->add(HttpMethod::fromString('get')->value, $route, $callable);
    }

    /**
     * Set POST routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function post(string $route, callable|array $callable): RouterInterface
    {
        return $this->add(HttpMethod::fromString('post')->value, $route, $callable);
    }

    /**
     * Set PUT routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function put(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('put')->value, $route, $callable);
        return $this;
    }

    /**
     * Set DELETE routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function delete(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('delete')->value, $route, $callable);
        return $this;
    }

    /**
     * Set PATCH routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function patch(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('patch')->value, $route, $callable);
        return $this;
    }

    /**
     * Set HEAD routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function head(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('head')->value, $route, $callable);
        return $this;
    }

    /**
     * Set OPTIONS routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function options(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('options')->value, $route, $callable);
        return $this;
    }

    /**
     * Set CONNECT routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function connect(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('connect')->value, $route, $callable);
        return $this;
    }

    /**
     * Set TRACE routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function trace(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('trace')->value, $route, $callable);
        return $this;
    }

    /**
     * Set ANY routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function any(string $route, callable|array $callable): RouterInterface
    {
        $this->add(HttpMethod::fromString('any')->value, $route, $callable);

        return $this;
    }


    /**
     * Group routes under a common prefix.
     *
     * @param string $prefix
     * @param callable $callback Receives $this router instance
     * @param string|array $middleware
     *
     * @return BaseRouter
     */
    public function group(string $prefix, callable $callback, string|array $middleware): BaseRouter
    {
        $previousGroup = $this->currentGroupPrefix;
        $previousMiddleware = $this->currentGroupMiddleware ?? [];

        $this->currentGroupPrefix .= '/' . trim($prefix, '/');
        $this->currentGroupMiddleware = array_merge($this->currentGroupMiddleware, $middleware);

        $callback($this);

        $this->currentGroupPrefix = $previousGroup;
        $this->currentGroupMiddleware = $previousMiddleware;

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
        $this->get($prefix . '/create', [$controller, 'create']);
        $this->post($prefix, [$controller, 'store']);
        $this->get($prefix . '/{id}', [$controller, 'show']);
        $this->get($prefix . '/{id}/edit', [$controller, 'edit']);
        $this->put($prefix . '/{id}', [$controller, 'update']);
        $this->delete($prefix . '/{id}', [$controller, 'destroy']);

        return $this;
    }

    /**
     * Register a route for one or many HTTP methods.
     *
     * @param ServerRequestInterface $request
     *
     * @return MatchedRoute
     *
     * @throws NotFoundException|MethodNotAllowedException
     */
    public function match(ServerRequestInterface $request): MatchedRoute
    {
        $method = strtoupper($request->getMethod());
        $path   = $request->getUri()->getPath();

        $routes = $this->routes->forMethod($method);

        $matched = $this->strategy->match($method, $path, $routes);

        if ($matched !== null) {
            return $matched;
        }

        $this->assertMethodAllowed($method, $path);

        throw new NotFoundException('Route {' . $path . '} not found');
    }

    /**
     * Register a route for one or many HTTP methods.
     *
     * @param string $method
     * @param string $path
     *
     * @return void
     *
     * @throws MethodNotAllowedException
     */
    private function assertMethodAllowed(string $method, string $path): void
    {
        $allowed = [];

        foreach ($this->routes->all() as $m => $routes) {
            if ($m === $method) {
                continue;
            }

            if ($this->strategy->match($m, $path, $routes)) {
                $allowed[] = $m;
            }
        }

        if ($allowed) {
            throw new MethodNotAllowedException(
                'Method {' . $method . '} not allowed for {' . $path . '}'
            );
        }
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
        $method = strtoupper($method);
        $methodsToCheck = [$method];

        if (isset($this->routes['ANY'])) {
            $methodsToCheck[] = 'ANY';
        }

        foreach ($methodsToCheck as $m) {
            if (!isset($this->routes[$m])) {
                continue;
            }

            foreach ($this->routes[$m] as $entry) {
                if ($entry['route'] === $route) {
                    return $entry;
                }
            }
        }

        return null;
    }
}
