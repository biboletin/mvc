<?php

namespace Bibo\Mvc\Core\Interfaces;

use Bibo\Mvc\Core\Request\BaseRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * BaseRouter interface
 */
interface RouterInterface
{
    /**
     * Set GET routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function get(string $route, array|callable $callable): self;

    /**
     * Set POST routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function post(string $route, array|callable $callable): self;

    /**
     * Set PUT routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function put(string $route, array|callable $callable): self;

    /**
     * Set DELETE routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function delete(string $route, array|callable $callable): self;

    /**
     * Set PATCH routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function patch(string $route, array|callable $callable): self;

    /**
     * Set HEAD routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function head(string $route, array|callable $callable): self;

    /**
     * Set OPTIONS routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function options(string $route, array|callable $callable): self;

    /**
     * Set CONNECT routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function connect(string $route, array|callable $callable): self;

    /**
     * Set TRACE routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function trace(string $route, array|callable $callable): self;

    /**
     * Set ANY routes
     *
     * @param string         $route
     * @param array|callable $callable
     *
     * @return RouterInterface
     */
    public function any(string $route, array|callable $callable): self;

    /**
     * Group routes
     *
     * @param string $prefix
     * @param callable $callback
     * @param string|array $middleware
     *
     * @return RouterInterface
     */
    public function group(string $prefix, callable $callback, string|array $middleware): self;

    /**
     * Redirect route
     *
     * @param string $from
     * @param string $to
     * @param int    $status
     *
     * @return RouterInterface
     */
    public function redirect(string $from, string $to, int $status): self;

    /**
     * Resource route
     *
     * @param string $prefix
     * @param string $controller
     *
     * @return RouterInterface
     */
    public function resource(string $prefix, string $controller): self;
}
