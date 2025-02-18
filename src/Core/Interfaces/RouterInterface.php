<?php

namespace Bibo\Core\Interfaces;

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
     * Group routes
     *
     * @param string   $name
     * @param callable $callable
     *
     * @return void
     */
    public function group(string $name, callable $callable): void;

    /**
     * Match route
     *
     * @param string $method
     * @param string $uri
     *
     * @return ResponseInterface
     */
    public function match(string $method, string $uri): ResponseInterface;

    /**
     * Add middleware
     *
     * @param callable $middleware
     *
     * @return void
     */
    public function middleware(callable $middleware): void;

    /**
     * Find route
     *
     * @param string $method
     * @param string $route
     *
     * @return array|null
     */
    public function find(string $method, string $route): ?array;

    /**
     * Set route name
     *
     * @param string $name
     *
     * @return void
     */
    public function name(string $name): void;

    /**
     * Dump routes
     *
     * @return void
     */
    public function dump(): void;
}
