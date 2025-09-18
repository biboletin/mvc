<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\ScriptExecutionTimer\ScriptExecutionTimer;
use Psr\Container\NotFoundExceptionInterface;

class ScriptExecutionTimerServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @return void
     */
    public function register(): void
    {
        $scriptExecutionTimer = new ScriptExecutionTimer();

        $this->container->set(ScriptExecutionTimer::class, function () use ($scriptExecutionTimer) {
            return $scriptExecutionTimer;
        });
    }

    /**
     * Boot service provider
     *
     * @return void
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
