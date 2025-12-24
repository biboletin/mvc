<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\ScriptExecutionTimer\ScriptExecutionTimer;

class ScriptExecutionTimerServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(ScriptExecutionTimer::class, fn () => new ScriptExecutionTimer());
    }

    /**
     * Boot service provider
     *
     * @return void
     */
    public function boot(): void
    {
    }
}
