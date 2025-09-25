<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\LogManager;
use Bibo\Mvc\Core\Resolver\ArgumentResolver;
use Psr\Container\NotFoundExceptionInterface;

class ResolverServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $argumentResolver = new ArgumentResolver($this->container);

        $this->container->set(ArgumentResolver::class, fn () => $argumentResolver);
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container
            ->get(LogManager::class)
            ->get('app')
            ->debug(__CLASS__ . ' booted successfully');
    }
}
