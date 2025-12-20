<?php

namespace Bibo\Mvc\Core\Container;

use AllowDynamicProperties;
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
     *     A → B → A (circular dependency)
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
     * List of prototypes that should not be shared.
     * This is useful for services that require parameters,
     * as they will be instantiated every time they are retrieved.
     * Example:
     *     $container->prototype(Database::class, fn(ContainerInterface $container) => new Database($container->get('config')));
     *
     * @var array<string, bool>
     */
    private array $prototypes = [];

    /**
     * List of factory-defined services that should not be shared.
     * This is useful for services that require parameters,
     * as they will be instantiated every time they are retrieved.
     * Example:
     *     $container->factory(Database::class, fn(ContainerInterface $container) => new Database($container->get('config')));
     *
     * @var array<string, bool>
     */
    private array $nonShared = [];

    /**
     * Whether to autowire classes that do not have a binding.
     * Defaults to true.
     * Set to false to disable autowiring.
     *
     * @var bool
     */
    private bool $autowireAsSingleton = true;

    /**
     * Indicates whether the container is frozen (immutable).
     *
     * @var bool
     */
    private bool $frozen = false;

    /**
     * Parameters to be passed to the container when invoking callables.
     * This is useful for passing configuration values to controllers or middleware.
     * Example:
     *     $container->call(function (LoggerInterface $logger) {
     *         $logger->info('Hello world!');
     *     }, ['config' => $config]);
     *
     * @var array<string, mixed>
     */
    private array $parameters = [];

    /**
     * Constructor
     *
     * @param array<string, Closure> $bindings
     * @param array<string, string> $aliases
     */
    public function __construct(array $bindings = [], array $aliases = [])
    {
        $this->resolving = new SplStack();
        $this->bindings = $bindings;
        $this->aliases = $aliases;

        // ✔ Container should resolve itself
        $this->aliases[ContainerInterface::class] = self::class;
        $this->bindings[self::class] = fn() => $this;
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
        // instance cached
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        // alias lookup (before binding)
        if (isset($this->aliases[$id])) {
            $id = $this->aliases[$id];
        }

        // explicit binding
        if (isset($this->bindings[$id])) {
            try {
                $object = ($this->bindings[$id])($this);

                // singleton unless registered with factory()
                if (!isset($this->nonShared[$id])) {
                    $this->instances[$id] = $object;
                }

                return $object;
            } catch (Throwable $e) {
                throw new ContainerException("Failed to resolve binding '" . $id . "'");
            }
        }

        // autowire fallback
        if (class_exists($id)) {
            if (isset($this->prototypes[$id])) {
                return $this->resolve($id);
            }

            return $this->instances[$id] ??= $this->resolve($id);
        }

        throw new ContainerItemNotFoundException("No entry found for '" . $id . "'");
    }

    /**
     * Retrieve a prototype service from the container.
     * Prototypes are identical to singletons, except that they are not cached.
     * This is useful for services that require parameters,
     * as they will be instantiated every time they are retrieved.
     * Example:
     *     $container->prototype(Database::class, fn(ContainerInterface $container) => new Database($container->get('config')));
     *
     * @param string $class
     *
     * @return static
     */
    public function prototype(string $class): Container
    {
        $this->prototypes[$class] = true;

        return $this;
    }

    /**
     * Register a scalar parameter for constructor injection.
     *
     * Parameters are matched by constructor parameter name.
     * Used for injecting configuration values (dsn, paths, flags, etc.).
     *
     * Example:
     *   $container->parameter('dsn', 'mysql:host=localhost;dbname=test');
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     *
     * @throws ContainerException
     */
    public function parameter(string $name, mixed $value): self
    {
        $this->assertNotFrozen();

        $this->parameters[$name] = $value;

        return $this;
    }

    /**
     * Freeze the container, making it immutable.
     *
     * After freezing, no new bindings, aliases, or parameters
     * can be registered.
     *
     * Intended for production use.
     *
     * @return void
     */
    public function freeze(): void
    {
        $this->frozen = true;
    }

    /**
     * Ensure the container is not frozen.
     *
     * @throws ContainerException
     */
    private function assertNotFrozen(): void
    {
        if ($this->frozen) {
            throw new ContainerException('Container is frozen and cannot be modified.');
        }
    }

    /**
     * Define a service in the container.
     *
     * @param string $id Identifier of the entry.
     * @param Closure $concrete Factory closure that returns the service.
     *
     * @return $this
     *
     * @throws ContainerException
     */
    public function set(string $id, Closure $concrete): self
    {
        $this->assertNotFrozen();

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
     *
     * @throws ContainerException
     */
    public function alias(string $abstract, string $concrete): self
    {
        $this->assertNotFrozen();

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
     * @param string $id Service identifier.
     * @param Closure $factory Factory that returns the service instance.
     *
     * @return $this
     *
     * @throws ContainerException
     */
    public function singleton(string $id, Closure $factory): self
    {
        $this->assertNotFrozen();

        return $this->set($id, $factory);
    }

    /**
     * Register a factory service that returns a *new instance every time*.
     *
     * Unlike `set()` and `singleton()`, factory services are not cached
     * inside `$instances`, so each call to `get()` resolves a fresh object.
     *
     * @param string $id Service identifier.
     * @param Closure $factory Factory invoked on each retrieval.
     *
     * @return $this
     *
     * @throws ContainerException
     */
    public function factory(string $id, Closure $factory): self
    {
        $this->assertNotFrozen();

        $this->bindings[$id] = $factory;
        $this->nonShared[$id] = true;

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

            $type = $param->getType();

            // Built-in types (string, int, bool, array, etc.)
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                $paramName = $param->getName();

                // 1. explicit parameter binding
                if (array_key_exists($paramName, $this->parameters)) {
                    $dependencies[] = $this->parameters[$paramName];
                    continue;
                }

                // 2. default value
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                    continue;
                }

                throw new ContainerException('Cannot resolve scalar parameter ' . $paramName . ' in ' . $class);
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
     *
     * @throws ContainerException
     */
    public function remove(string $id): void
    {
        $this->assertNotFrozen();

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
        $reflection = $this->getCallableReflection($callable);
        $args = [];

        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            // 1. manual parameter
            if (array_key_exists($name, $parameters)) {
                $args[] = $parameters[$name];
                continue;
            }

            // 2. autowiring for class types
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $args[] = $this->get($type->getName());
                continue;
            }

            // 3. default parameter
            if ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
                continue;
            }

            throw new ContainerException('Missing required parameter {' . $name . '}');
        }

        return $callable(...$args);
    }

    /**
     * Get the ReflectionFunction or ReflectionMethod for a callable.
     * This is used internally by the `call()` method to inspect the callable's parameters.
     *
     * @param callable $callable
     *
     * @return ReflectionFunction|ReflectionMethod
     * @throws ReflectionException
     */
    private function getCallableReflection(callable $callable): ReflectionFunction|ReflectionMethod
    {
        // array callable: [Class, 'method']
        if (is_array($callable)) {
            return new ReflectionMethod($callable[0], $callable[1]);
        }

        // "Class::method" static string
        if (is_string($callable) && str_contains($callable, '::')) {
            [$class, $method] = explode('::', $callable);
            return new ReflectionMethod($class, $method);
        }

        // Closure or named function
        return new ReflectionFunction($callable);
    }

    /**
     * Magic method to set a binding in the container.
     * This allows you to use the container as an array-like structure.
     *
     * @param string $id
     * @param callable $concrete
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function __set(string $id, callable $concrete): void
    {
        $this->set($id, $concrete);
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
}
