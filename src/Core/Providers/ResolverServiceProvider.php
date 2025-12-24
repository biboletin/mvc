<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Resolver\ArgumentResolver;

class ResolverServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $argumentResolver = new ArgumentResolver($this->container);

        $this->container->set(ArgumentResolver::class, fn () => $argumentResolver);
    }

    /**
     * Boot the service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
