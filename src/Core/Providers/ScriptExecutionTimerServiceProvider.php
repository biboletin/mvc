<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\ScriptExecutionTimer\ScriptExecutionTimer;

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

        $this->container->set('timer', function () use ($scriptExecutionTimer) {
            return $scriptExecutionTimer;
        });
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
