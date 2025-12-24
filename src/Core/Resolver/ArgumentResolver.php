<?php

namespace Bibo\Mvc\Core\Resolver;

use Bibo\Mvc\Core\Request\BaseRequest;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionParameter;
use RuntimeException;

class ArgumentResolver
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * Constructor
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve arguments for a controller method or callable.
     *
     * @param callable|array $handler Controller [class, method] or callable
     * @param array          $params  Route parameters
     *
     * @return array
     *
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException|ContainerExceptionInterface
     */
    public function resolve(callable|array $handler, array $params = []): array
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            $reflection = new ReflectionMethod($class, $method);
        } else {
            $reflection = new ReflectionFunction($handler(...));
        }

        $arguments = [];

        foreach ($reflection->getParameters() as $parameter) {
            $arguments[] = $this->resolveParameter($parameter, $params);
        }

        return $arguments;
    }

    /**
     * Resolve a single parameter value.
     *
     * @param ReflectionParameter $parameter Parameter reflection
     * @param array               $params    Route parameters
     *
     * @return mixed
     *
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface
     */
    private function resolveParameter(ReflectionParameter $parameter, array &$params): mixed
    {
        $type = $parameter->getType();

        // If the type is specified and not a scalar
        if ($type && !$type->isBuiltin()) {
            $typeName = $type->getName();

            // Auto-inject Request
            $isServerRequest = is_a($typeName, ServerRequestInterface::class, true);
            $isBaseRequest = is_a($typeName, BaseRequest::class, true);

            if ($isServerRequest || $isBaseRequest) {
                return $this->container->get(BaseRequest::class);
            }

            // Try to resolve from container
            if ($this->container->has($typeName)) {
                return $this->container->get($typeName);
            }
        }

        // Fallback: use route param (FIFO)
        if (!empty($params)) {
            return array_shift($params);
        }

        // If no value found but default exists
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        // Could not resolve → throw
        throw new RuntimeException('Cannot resolve argument \$' . $parameter->getName());
    }
}
