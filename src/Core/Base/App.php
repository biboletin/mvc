<?php

namespace Bibo\Core\Base;

use Bibo\Core\Request\Stream;
use Bibo\Core\Response\JsonResponse;
use Bibo\Core\Response\RedirectResponse;
use Bibo\Core\Response\ResponseEmitter;
use Closure;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * App class
 */
class App
{
    /**
     * Container
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
     * Container
     *
     * @return ContainerInterface
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * Run app
     *
     * @return void
     * @throws JsonException
     */
    public function run(): void
    {
        $responseEmitter = new ResponseEmitter();
        try {
            $router = $this->container->get('router');
            $request = $this->container->get('request');

            // Extract HTTP method and URI from request
            $method = $request->getMethod();  // Ensure this returns a string
            $uri = $request->getUri()->getPath(); // Ensure this returns a string

            // Match the route using method and path
            $response = $router->match($method, $uri);

            // Emit the response
            $responseEmitter->emit($response);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface | JsonException $e) {
            $response = new JsonResponse(['error' => 'Service unavailable'], 503);
            $responseEmitter->emit($response);
        }
    }


    /**
     * Handle the response based on the matched route
     *
     * @param mixed             $route
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     * @throws JsonException
     */
    private function handleResponse($route, ResponseInterface $response): ResponseInterface
    {
        // Here you could check the route type or controller action and handle it accordingly
        // For simplicity, let's assume $route could be a closure or a controller action.

        if ($route instanceof Closure) {
            // Call closure-based route
            return $route($response);
        }

        // If the route requires a JSON response
        if (is_array($route) && isset($route['type']) && $route['type'] === 'json') {
            return new JsonResponse($route['data']);
        }

        // If the route requires a redirect
        if (is_array($route) && isset($route['redirect'])) {
            return new RedirectResponse($route['redirect']);
        }

        // Default BaseResponse
        return $response->withStatus(404)->withBody(new Stream(fopen('php://temp', 'r+')));//->write('Not Found');
    }
}
