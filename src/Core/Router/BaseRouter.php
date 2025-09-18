<?php

namespace Bibo\Mvc\Core\Router;

use Bibo\Mvc\Core\Enums\HttpMethod;
use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Interfaces\RouteMatchingStrategyInterface;
use Bibo\Mvc\Core\Interfaces\RouterInterface;
use Bibo\Mvc\Core\Middleware\MiddlewareDispatcher;
use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Request\Stream;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Response\RedirectResponse;
use Bibo\Mvc\Core\View\View;
use Exception;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

    private MiddlewareDispatcher $dispatcher;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container, ?RouteMatchingStrategyInterface $strategy = null)
    {
        $this->strategy = $strategy ?? new CachedRegexMatchStrategy();
        $this->container = $container;
        try {
            $this->dispatcher = $this->container->get(MiddlewareDispatcher::class);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
        }
    }

    /**
     * Add route to container
     *
     * @param string         $method
     * @param string         $route
     * @param array|callable $handler
     * @param array|null     $middleware
     * @param string|null    $name
     *
     * @return void
     */
    private function add(
        string $method,
        string $route,
        array|callable $handler,
        ?array $middleware = null,
        ?string $name = null
    ): void {
        // Build full route with group prefix if present
        $fullRoute = ($this->currentRouteGroup ? '/' . trim($this->currentRouteGroup, '/') : '')
            . '/' . trim($route, '/');

        // Normalize: replace '//' with '/' so root routes don’t break
        $fullRoute = preg_replace('#//+#', '/', $fullRoute);

        $this->routes[] = [
            'method'     => strtoupper($method),
            'route'      => $fullRoute,
            'handler'    => $handler,
            'middleware' => $middleware,
            'name'       => $name,
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
     * Group routes
     *
     * @param string   $name
     * @param callable $callable
     *
     * @return BaseRouter
     */
    public function group(string $name, callable $callable): BaseRouter
    {
        $previousRouteGroup = $this->currentRouteGroup;

        // update prefix for this group
        $this->currentRouteGroup = rtrim($previousRouteGroup . '/' . trim($name, '/'), '/');

        // execute closure
        $callable();

        // restore prefix after closure finishes
        $this->currentRouteGroup = $previousRouteGroup;

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
     * Match route
     *
     * @param string|array $method
     * @param string       $uri
     *
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws JsonException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    public function matchRoutes(string|array $method, string $uri): ResponseInterface
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                if ($this->strategy->matches($route['route'], $uri, $params)) {
                    return $this->handleRoute($route, $params);
                }
            }
        }

        throw new NotFoundException('Route ' . $uri . ' not found!', HttpStatus::NotFound->value);
    }

    /**
     * Handles route
     *
     * @throws JsonException
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
     */
    private function handleCallable(callable $handler, array $params = []): ResponseInterface
    {
        ob_start(); // Capture echoed output
        $response = call_user_func_array($handler, $params);
        $output = ob_get_clean(); // Get the output

        // If the handler returns a ResponseInterface, return it
        if ($response instanceof ResponseInterface) {
            return $response;
        }

        if (is_string($response)) {
            return new HtmlResponse($response);
        }

        // If handler echoes output, wrap it in a Response
        if (!empty($output)) {
            return new HtmlResponse($output); // Ensure you have an HtmlResponse class
        }

        // Default to an empty JSON response if nothing is returned
        return new JsonResponse(['message' => 'No content'], 200);
    }


    /**
     * Handle controller action
     *
     * @throws Exception
     */
    private function handleController(array $handler, array $params = []): ResponseInterface
    {
        [$controller, $method] = $handler;

        if (!class_exists($controller)) {
            throw new NotFoundException('Controller ' . $controller . ' not found');
        }

        $instance = new $controller($this->container);

        if (!method_exists($instance, $method)) {
            throw new NotFoundException('Method ' . $method . ' not found in ' . $controller);
        }

        $response = call_user_func_array([$instance, $method], $params);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if ($response instanceof View) {
            return new HtmlResponse($response->render());
        }

        return new HtmlResponse($response);
    }


    /**
     * Creates a stream from a string
     */
    private function createStream(string $content): Stream
    {
        $stream = new Stream(fopen('php://temp', 'r+'));
        $stream->write($content);
        $stream->rewind();
        return $stream;
    }


    /**
     * Add middleware
     *
     * @param string|array $middleware
     *
     * @return BaseRouter
     */
    public function middleware(string|array $middleware): BaseRouter
    {
        $lastRouteKey = array_key_last($this->routes);
        if ($lastRouteKey !== null) {
            $this->routes[$lastRouteKey]['middleware'] = $middleware;
        }

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
     * Set route name
     *
     * @param string $name
     *
     * @return BaseRouter
     */
    public function name(string $name): BaseRouter
    {
        $lastRouteKey = array_key_last($this->routes);
        if ($lastRouteKey !== null) {
            $this->routes[$lastRouteKey]['name'] = $name;
        }

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
     * Dump routes
     *
     * @return void
     */
    public function dump(): void
    {
        foreach ($this->routes as $route) {
            $method     = $route['method'];
            $fullRoute  = $route['route'];
            $handler    = is_callable($route['handler'])
                ? 'callable'
                : (is_array($route['handler']) ? implode('::', $route['handler']) : $route['handler']);
            $name       = $route['name'] ?? 'No name';
            $middleware = isset($route['middleware'])
                ? (is_array($route['middleware'])
                    ? count($route['middleware']) . ' middleware(s)' : '1 middleware') : 'No middleware';

            echo sprintf(
                "[%s] <b>%s</b> -> %s, Name: %s, Middleware: %s <br>\n",
                $method,
                $fullRoute,
                $handler,
                $name,
                $middleware
            );
        }
    }
}
