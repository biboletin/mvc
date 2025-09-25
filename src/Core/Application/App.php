<?php

namespace Bibo\Mvc\Core\Application;

use Bibo\App\Middleware\ErrorMiddleware;
use Bibo\Mvc\Core\Error\Error;
use Bibo\Mvc\Core\Error\ErrorResponseFactory;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Response\ResponseEmitter;
use Bibo\Mvc\Core\Router\BaseRouter;
use Bibo\Mvc\Core\Traits\NameAwareTrait;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 * App class
 */
class App
{
    use NameAwareTrait;

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
     * @throws ContainerExceptionInterface|NotFoundException
     */
    public function run(): void
    {
        $responseEmitter = $this->container->get(ResponseEmitter::class);

        try {
            // Get PSR-7 request
            $request = $this->container->get(BaseRequest::class);
            // Build the middleware stack
            $router = $this->container->get(BaseRouter::class);
            $errorMiddleware = $this->container->get(ErrorMiddleware::class);

            // Dispatch the request through middleware
            // Simplified: ErrorMiddleware wraps the router call
            $response = $errorMiddleware->process(
                $request,
                new class ($router) implements RequestHandlerInterface {
                    private BaseRouter $router;
                    public function __construct($router)
                    {
                        $this->router = $router;
                    }
                    public function handle(ServerRequestInterface $request): ResponseInterface
                    {
                        // Router executes controller/action, may throw exceptions
                        return $this->router->dispatch($request);
                    }
                }
            );

            // Emit final response
            $responseEmitter->emit($response);
        } catch (Throwable $e) {
            // Last-resort fallback if middleware fails
            $errorHandler = $this->container->get(Error::class);
            $errorHandler->handleException($e);

            $errorData = $errorHandler->normalize($e);
            $factory = $this->container->get(ErrorResponseFactory::class);

            // Pass the request if is available, or create a fake one
            $response = $factory->createResponse($errorData, $request ?? new BaseRequest());
            $responseEmitter->emit($response);
        }
    }
}
