<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\Template\Template;
use Bibo\Mvc\Core\Wrapper\TwigTemplateEngine;
use Psr\Container\NotFoundExceptionInterface;

class TemplateServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @inheritDoc
     * @throws     NotFoundExceptionInterface
     */
    public function register(): void
    {
        $config = $this->container->get(ConfigHandler::class);
        $templateEngine = new TwigTemplateEngine(
            VIEW_PATH,
            $config->get('cache.enabled'),
            APP_CACHE_PATH,
            $config->get('app.debug')
        );
        $template = new Template($templateEngine);

        $this->container->set(Template::class, function () use ($template) {
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
        $this->container->get(Logger::class)->debug(__CLASS__ . ' booted successfully');
    }
}
