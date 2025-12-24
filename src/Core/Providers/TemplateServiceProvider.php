<?php

namespace Bibo\Mvc\Core\Providers;

use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Exception\Custom\Container\ContainerException;
use Bibo\Mvc\Core\Template\Template;
use Bibo\Mvc\Core\Wrapper\TwigTemplateEngine;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class TemplateServiceProvider extends ServiceProvider
{
    /**
     * Register service provider
     *
     * @inheritDoc
     *
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->container->set(Template::class, fn (ContainerInterface $container) => new Template($container));
    }

    /**
     * Boot the service provider
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function boot(): void
    {
        $config = $this->container->get(ConfigHandler::class);

        $templateEngine = new TwigTemplateEngine(
            VIEW_PATH,
            $config->get('cache.enabled'),
            APP_CACHE_PATH,
            $config->get('app.debug')
        );

        $template = $this->container->get(Template::class);
        $template->setEngine($templateEngine);
    }
}
