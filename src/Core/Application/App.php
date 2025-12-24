<?php

namespace Bibo\Mvc\Core\Application;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Facades\Env;
use Bibo\Mvc\Core\Interfaces\ServiceProviderInterface;
use Bibo\Mvc\Core\Traits\NameAwareTrait;
use Bibo\Mvc\Core\Traits\PrefixAwareTrait;
use Bibo\Mvc\Core\Traits\VersionAwareTrait;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * App class
 */
final class App
{
    use NameAwareTrait;
    use PrefixAwareTrait;
    use VersionAwareTrait;

    /**
     * Container
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Providers
     *
     * @var array
     */
    private array $providers = [];

    /**
     * Indicates if the application has been booted
     *
     * @var bool
     */
    private bool $booted = false;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    // ------------------- Lifecycle -------------------

    /**
     * Bootstrap application
     *
     * @throws ContainerException
     */
    public function bootstrap(): void
    {
        $this->registerConfiguredProviders();
        $this->registerBaseBindings();
    }

    /**
     * Boot application
     *
     * @throws ContainerExceptionInterface
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        foreach ($this->providers as $providerClass) {
            $provider = new $providerClass($this->container());

            if (!($provider instanceof ServiceProviderInterface)) {
                throw new ContainerException($providerClass . ' must be instance of ServiceProviderInterface.');
            }

            $provider->register();
            $provider->boot();

            $this->container()->set($providerClass, fn () => $provider);
        }

        $this->booted = true;
    }

    /**
     * Register base bindings
     *
     * @throws ContainerException
     */
    private function registerBaseBindings(): void
    {
        $this->container()->set(App::class, fn () => $this);
    }

    // ------------------- Providers -------------------

    /**
     * Register providers from bootstrap file
     *
     * @return void
     */
    public function registerConfiguredProviders(): void
    {
        $providers = include __DIR__ . '/../../../bootstrap/bootstrap.php';

        $this->providers = $providers;
    }

    // ------------------- Environment -------------------

    /**
     * Get environment
     *
     * @return string
     */
    public function environment(): string
    {
        return Env::get()->value;
    }

    /**
     * Check if the current environment is production.
     *
     * @return bool
     */
    public function isProduction(): bool
    {
        return Env::isProduction();
    }

    /**
     * Check if the current environment is development.
     *
     * @return bool
     */
    public function isDevelopment(): bool
    {
        return Env::isDevelopment();
    }

    /**
     * Check if the current environment is staging.
     *
     * @return bool
     */
    public function isStaging(): bool
    {
        return Env::isStaging();
    }

    /**
     * Check if the current environment is testing.
     *
     * @return bool
     */
    public function runningUnitTests(): bool
    {
        return Env::isTesting();
    }

    /**
     * Check if the application is running in the console.
     *
     * @return bool
     */
    public function runningInConsole(): bool
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * Get version
     *
     * @return string
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function version(): string
    {
        return $this->config('app.version', '1.0.0');
    }

    // ------------------- Config -------------------

    /**
     * Get config value
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function config(string $key, mixed $default = null): mixed
    {
        return $this->container->get(ConfigHandler::class)->get($key, $default);
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
}
