<?php

namespace Bibo\Core\Provider;

use Bibo\Core\Template\Template;
use Bibo\Core\Wrapper\TwigTemplateEngine;
use Bibo\Mvc\Core\Providers\ServiceProvider;
use Psr\Container\NotFoundExceptionInterface;

class TemplateServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @inheritDoc
     */
    public function register(): void
    {
        $templateEngine = new TwigTemplateEngine(VIEW_PATH, APP_CACHE_PATH, config('app_debug'));
        $template = new Template($templateEngine);

        $this->container->set('template', function () use ($template) {
            return $template;
        });
    }

    /**
     * Boot service provider
     *
     * @throws NotFoundExceptionInterface
     */
    public function boot(): void
    {
        $this->container->get('logger')->debug(__CLASS__ . ' booted successfully');
    }
}
