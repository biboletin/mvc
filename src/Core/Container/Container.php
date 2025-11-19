<?php

namespace Bibo\Mvc\Core\Container;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerItemNotFoundException;
use Closure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use SplStack;
use Throwable;

/**
 * ---------------------------------------------------------------------------
 *  Bibo\Mvc\Core\Container
 * ---------------------------------------------------------------------------
 *
 * A fully-featured Dependency Injection Container implementation compliant
 * with the PSR-11 standard. This container provides a flexible and modern
 * DI system suitable for frameworks, libraries, and standalone applications.
 *
 * Key Features:
 *  - Manual service binding (singletons + factory definitions)
 *  - Autowiring using reflection (constructor injection)
 *  - Interface → implementation aliasing
 *  - Circular dependency detection
 *  - Lazy service instantiation
 *  - Invocation of callables with auto-injected dependencies
 *  - PSR-11 compatibility (`get()`, `has()`)
 *
 * This container allows developers to mix explicit bindings with automatic
 * dependency resolution, providing the convenience of autowiring without
 * sacrificing control and explicit configurability.
 */
final class Container implements ContainerInterface
{
    /**
     * Service bindings (definitions).
     *
     * Each entry maps an ID to a Closure that receives the container
     * and returns an instance of that service.
     *
     * @var array<string, Closure>
     */
    private array $bindings = [];

    /**
     * Already-created shared service instances.
     *
     * @var array<string, mixed>
     */
    private array $instances = [];

    /**
     * Interface/abstract → implementation class mappings.
     *
     * Used by the autowiring system to determine which concrete class should
     * be instantiated when a constructor requests an interface or abstract.
     *
     * Example:
     *     $container->alias(LoggerInterface::class, FileLogger::class);
     *
     * @var array<string, string>
     */
    private array $aliases = [];

    /**
     * Resolution trace stack for circular dependency detection.
     *
     * When autowiring, each class currently being resolved is pushed into
     * this array. If a class depends (directly or indirectly) on itself,
     * the container throws a descriptive exception.
     *
     * Example:
     *     A → B → A   (circular dependency)
     *
     * @var SplStack<string>
     */
    private SplStack $resolving;

    /**
     * Reflection cache for resolved classes.
     *
     * @var array
     */
    private array $reflections = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resolving = new SplStack();
    }

    /**
     * Retrieve an entry of the container by its identifier.
     *
     * @param string $id Identifier of the service to retrieve.
     *
     * @return mixed The resolved service instance.
     *
     * @throws NotFoundExceptionInterface  If no entry is found for the identifier.
     * @throws ContainerExceptionInterface If resolving the entry fails.
     * @throws ReflectionException
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!isset($this->bindings[$id])) {
            throw new ContainerItemNotFoundException('Item ' . $id . ' not found!');
        }

        try {
            $this->instances[$id] = ($this->bindings[$id])($this);
        } catch (Throwable $exception) {
            throw new ContainerItemNotFoundException(
                'Failed to resolve service ' . $id . ': ' . $exception->getMessage(),
                0,
                $exception
            );
        }

        // If it's bound, use the bound instance
        if (isset($this->bindings[$id])) {
            $this->instances[$id] = ($this->bindings[$id])($this);
            return $this->instances[$id];
        }

        // If not bound but class exists, autowire it
        if (class_exists($id)) {
            return $this->instances[$id] = $this->resolve($id);
        }


        throw new ContainerItemNotFoundException("No entry found for '{" . $id . "}'.");
    }

    /**
     * Define a service in the container.
     *
     * @param string  $id       Identifier of the entry.
     * @param Closure $concrete Factory closure that returns the service.
     *
     * @return $this
     */
    public function set(string $id, Closure $concrete): self
    {
        $this->bindings[$id] = $concrete;

        return $this;
    }

    /**
     * Determine whether a service is defined or autowirable.
     *
     * This method checks:
     *  - explicit bindings
     *  - instantiated singletons
     *  - interface aliases
     *  - existence of a class that can be autowired
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id])
            || isset($this->instances[$id])
            || isset($this->aliases[$id])
            || class_exists($id);
    }

    /**
     * Register an alias mapping from an abstract (usually an interface)
     * to a concrete class. This is used by the autowiring engine.
     *
     * Example:
     *     $container->alias(LoggerInterface::class, FileLogger::class);
     *
     * @param string $abstract
     * @param string $concrete
     *
     * @return $this
     */
    public function alias(string $abstract, string $concrete): self
    {
        $this->aliases[$abstract] = $concrete;

        return $this;
    }

    /**
     * Register a shared (singleton) service.
     *
     * A singleton is identical to `set()` because this container already
     * stores resolved services. This method is provided for readability
     * and API clarity.
     *
     * @param string  $id       Service identifier.
     * @param Closure $factory  Factory that returns the service instance.
     *
     * @return $this
     */
    public function singleton(string $id, Closure $factory): self
    {
        return $this->set($id, $factory);
    }

    /**
     * Register a factory service that returns a *new instance every time*.
     *
     * Unlike `set()` and `singleton()`, factory services are not cached
     * inside `$instances`, so each call to `get()` resolves a fresh object.
     *
     * @param string  $id       Service identifier.
     * @param Closure $factory  Factory invoked on each retrieval.
     *
     * @return $this
     */
    public function factory(string $id, Closure $factory): self
    {
        $this->bindings[$id] = function (Container $c) use ($factory) {
            return $factory($c); // call fresh every time
        };

        return $this;
    }

    /**
     * Autowire a class or interface and return its constructed instance.
     *
     * This is a convenience wrapper for `get()` that makes intentions
     * explicit in userland code.
     *
     * @param string $class Fully-qualified class/interface name.
     *
     * @return mixed
     *
     * @throws NotFoundExceptionInterface|ContainerExceptionInterface|ReflectionException
     */
    public function autowire(string $class): mixed
    {
        dd($class);
        return $this->get($class);
    }

    /**
     * Resolve a class via reflection by inspecting its constructor and
     * recursively resolving its dependencies.
     *
     * Autowiring rules:
     *  - Public constructor required (or no constructor)
     *  - All parameters must have class type-hints
     *  - Built-in types (int, string, array, etc.) cannot be autowired
     *  - Interfaces must be mapped via alias()
     *
     * @param string $class The class name to resolve.
     *
     * @return mixed A fully constructed instance.
     *
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     *         When a parameter cannot be resolved.
     */
    private function resolve(string $class): mixed
    {
        // Handle interface → concrete alias
        if (isset($this->aliases[$class])) {
            $class = $this->aliases[$class];
        }

        // Check for circular dependencies
        if (in_array($class, iterator_to_array($this->resolving), true)) {
            throw new ContainerException(
                'Circular dependency detected: ' .
                implode(' → ', iterator_to_array($this->resolving)) . ' → {' . $class . '}'
            );
        }

        $this->resolving->push($class);

        if (!class_exists($class)) {
            throw new ContainerException("Class '{" . $class . "}' does not exist.");
        }

        // Use cached reflection class or create a new one
        if (!isset($this->reflections[$class])) {
            $this->reflections[$class] = new ReflectionClass($class);
        }

        $reflection = $this->reflections[$class];

        if (!$reflection->isInstantiable()) {
            throw new ContainerException("Class '{" . $class . "}' is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        // No constructor → directly instantiate
        if (!$constructor) {
            $this->resolving->pop();

            return new $class();
        }

        $dependencies = [];

        // Resolve constructor parameters
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException(
                    'Cannot autowire parameter \\' .
                    $param->getName() . ' in {' . $class . '} — invalid or missing type.'
                );
            }

            $dependencies[] = $this->get($type->getName());
        }

        $this->resolving->pop();

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Remove a service definition and its cached instance.
     *
     * Useful in long-running scripts (workers), or when testing.
     *
     * @param string $id Identifier of the service to remove.
     *
     * @return void
     */
    public function remove(string $id): void
    {
        unset($this->bindings[$id], $this->instances[$id]);
    }

    /**
     * Invoke a callable with automatic dependency injection.
     *
     * This allows controllers, handlers, middleware, etc. to be declared
     * with typed parameters that the container resolves automatically.
     *
     * Resolution rules:
     *  - Class type-hinted parameters → resolved via container
     *  - Manually provided parameters take precedence
     *  - Default parameter values are respected
     *
     * @param callable $callable The function/method to invoke.
     * @param array<string, mixed> $parameters Optional manually supplied parameters.
     *
     * @return mixed The return value of the invoked callable.
     *
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     *         If a parameter cannot be resolved.
     */
    public function call(callable $callable, array $parameters = []): mixed
    {
        $key = is_array($callable) ? $callable[0] . '::' . $callable[1] : (is_string($callable) ? $callable : '');

        if (!isset($this->reflections[$key])) {
            if (is_array($callable)) {
                $reflection = new ReflectionMethod($callable[0], $callable[1]);
            } elseif (is_string($callable) && str_contains($callable, '::')) {
                $reflection = new ReflectionMethod(...explode('::', $callable));
            } elseif ($callable instanceof Closure || is_string($callable)) {
                $reflection = new ReflectionFunction($callable);
            } else {
                throw new ContainerException('Invalid callable type');
            }
        } else {
            // If cached, just use it
            $reflection = $this->reflections[$key];
        }

        $args = [];

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // Class type → autowire
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $args[] = $this->get($type->getName());
                continue;
            }

            // Manual parameter?
            if (array_key_exists($name, $parameters)) {
                $args[] = $parameters[$name];
                continue;
            }

            // Use default value
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            // No resolution possible
            throw new ContainerException("Missing parameter '{" . $name . "}'");
        }

        return $callable(...$args);
    }

    /**
     * Magic method to set a binding in the container.
     * This allows you to use the container as an array-like structure.
     *
     * @param string   $id
     * @param callable $concrete
     *
     * @return void
     */
    public function __set(string $id, callable $concrete): void
    {
        $this->set($id, $concrete);
    }

    /**
     * Magic method to get a service from the container.
     * This allows you to use the container as an array-like structure.
     * It retrieves the service by its identifier.
     * If the service is not bound, it attempts to autowire it.
     * This method throws an exception if the service is not found.
     * It is useful for accessing services without explicitly calling the `get` method.
     * It is important to note that this method should not be used for services that require parameters,
     * as it will not handle parameter resolution.
     * It is recommended to use the `get` method for services that require parameters.
     * This method is also useful for accessing services that are registered as singletons,
     * as it will return the same instance every time it is called.
     * It is important to ensure that the service identifier is valid and that the service is properly registered in the container.
     * This method is a convenient way to access services in the container without having to call the `get` method explicitly.
     *
     * @param string $id
     *
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function __get(string $id)
    {
        return $this->get($id);
    }

    /**
     * Magic method to check if a service is bound in the container.
     * This allows you to use the container as an array-like structure.
     * It checks if the service identifier exists in the bindings.
     *
     * @param string $id
     *
     * @return bool
     */
    public function __isset(string $id): bool
    {
        return $this->has($id);
    }

    /**
     * Magic method to unset a service binding in the container.
     * This allows you to remove a service from the container.
     * It removes the service identifier from the bindings and instances arrays.
     *
     * @param string $id
     *
     * @return void
     */
    public function __unset(string $id): void
    {
        unset($this->bindings[$id], $this->instances[$id]);
    }

    /**
     * Magic method to call a method on the container.
     * This allows you to call methods on the container as if it were an object.
     * It checks if the method exists in the container and calls it with the provided arguments.
     * If the method does not exist, it throws a ContainerException.
     * This method is useful for accessing container methods dynamically,
     * such as when you want to call a method that is not explicitly defined in the container class.
     * @param string $method
     *                      The name of the method to call.
     *                      This method should be a valid method name that exists in the container class.
     *                      The method can be any public method defined in the container class.
     * @param array  $args
     *                    The arguments to pass to the method.
     *                    This should be an array of arguments that the method expects.
     *                    The arguments can be any type, including other services from the container.
     *                    This method is useful for dynamically calling methods on the container,
     *                    as it allows you to pass any number of arguments to the method.
     *                    This method is also useful for calling methods that require parameters,
     *                    as it allows you to pass the parameters directly to the method.
     *                    This method is not intended for calling methods that require specific parameters,
     *                    as it does not handle parameter resolution.
     *
     * @return mixed
     * @throws ContainerException
     */
    public function __call(string $method, array $args): mixed
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$args);
        }

        throw new ContainerException("Method '$method' does not exist in the container.");
    }
}
