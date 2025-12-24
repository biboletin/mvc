<?php

namespace Bibo\Mvc\Core\Template;

use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Interfaces\TemplateEngineInterface;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

/**
 * Template
 * This class is responsible for rendering templates using a template engine.
 * It provides a method to render a template with the given parameters.
 * It is designed to be used within a container, allowing for dependency injection.
 * The class is also responsible for handling the template engine instance.
 * It is a simple wrapper around the template engine to provide a consistent interface.
 * It is not responsible for any caching or other advanced features.
 * It is a basic implementation that can be extended or modified as needed.
 * It is designed to be used in a web application context.
 * It is not intended to be used in a command line context.
 * It is a simple and lightweight implementation that can be used in any PHP application.
 * It is not tied to any specific framework or library.
 * It is a standalone implementation that can be used in any PHP application.
 */
class Template
{
    /**
     * Template engine instance
     *
     * @var TemplateEngineInterface
     */
    private TemplateEngineInterface $engine;

    /**
     * Container instance
     *
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Set the template engine instance
     *
     * @param TemplateEngineInterface $engine
     *
     * @return void
     */
    public function setEngine(TemplateEngineInterface $engine): void
    {
        $this->engine = $engine;
    }

    public function getEngine(): TemplateEngineInterface
    {
        return $this->engine;
    }

    /**
     * Render a template with the given parameters
     *
     * @param string $view
     * @param array  $parameters
     *
     * @return string
     * @throws NotFoundException|InvalidArgumentException
     */
    public function render(string $view, array $parameters = []): string
    {
        $templateName = $this->templateToPath($view);

        // Check if the view file exists
        if (!file_exists(VIEW_PATH . $templateName)) {
            throw new NotFoundException('View [' . $view . '] not found!', 404);
        }

        return $this->engine->render($templateName, $parameters);
    }

    private function templateToPath(string $view): string
    {
        // Convert the view name to a path
        $path = str_replace('.', DIRECTORY_SEPARATOR, $view);
        // Add the .twig extension
        return $path . '.twig';
    }
}
