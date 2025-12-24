<?php

namespace Bibo\Mvc\Core\Router;

use Bibo\Mvc\Core\Resolver\ArgumentResolver;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use RuntimeException;

final readonly class RouteDispatcher
{
    public function __construct(
        private ContainerInterface $container,
        private ArgumentResolver $resolver,
    ) {
    }

    /**
     * Dispatch a route
     *
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function dispatch(
        MatchedRoute $route,
        ServerRequestInterface $request
    ): ResponseInterface {

        $handler = $route->handler();
        $params  = $route->params();

        $arguments = $this->resolver->resolve($handler, $params);

        if (is_callable($handler)) {
            return $this->normalize($handler(...$arguments));
        }

        [$class, $method] = $handler;
        $controller = $this->container->get($class);

        return $this->normalize(
            $controller->$method(...$arguments)
        );
    }

    /**
     * Normalize controller response
     *
     * @param mixed $result
     *
     * @return ResponseInterface
     */
    private function normalize(mixed $result): ResponseInterface
    {
        if ($result instanceof ResponseInterface) {
            return $result;
        }

        if (is_array($result)) {
            return new JsonResponse($result);
        }

        if (is_string($result)) {
            return new HtmlResponse($result);
        }

        throw new RuntimeException('Invalid controller response');
    }
}
