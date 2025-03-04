<?php

namespace Bibo\Core\Base;

use Bibo\Core\Exception\NotFoundException;
use Bibo\Core\Response\HtmlResponse;
use Bibo\Core\Response\JsonResponse;
use Bibo\Core\Response\ResponseEmitter;
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
            $response = new JsonResponse(['error' => $e->getMessage()], $e->getCode());
            $responseEmitter->emit($response);
        } catch (NotFoundException $e) {
            $response = new HtmlResponse($e->getMessage(), $e->getCode());
            $responseEmitter->emit($response);
        }
    }
}
