<?php

namespace Bibo\Mvc\Core\Base;

use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Response\ResponseEmitter;
use Exception;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

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
     * Get item from container
     *
     * @param string $item
     *
     * @return mixed
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function get(string $item): mixed
    {
        return $this->container->get($item);
    }

    /**
     * Run app
     *
     * @return void
     * @throws JsonException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
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
        } catch (ContainerExceptionInterface | JsonException $e) {
            $response = new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            $responseEmitter->emit($response);
        } catch (Exception $e) {
            $errorHandler = $this->container->get('error')->handleException($e);
            $responseEmitter->emit($errorHandler);
        }
    }
}
