<?php

namespace Bibo\Mvc\Core\View;

use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Template\Template;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * View
 * This class is responsible for rendering views using a template engine.
 * It uses a caching mechanism to store rendered views for better performance.
 * It also provides methods to assign variables to the template and render it.
 * The class is designed to be used within a container, allowing for dependency injection.
 */
class View
{
    /**
     * Template engine instance
     *
     * @var Template|mixed
     */
    protected Template $template;


    /**
     * View constructor
     *
     * @param ContainerInterface $container
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->template = $container->get(Template::class);
    }

    /**
     * Assign a variable to the template
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function assign(string $key, mixed $value): void
    {
        $this->template->assign($key, $value);
    }

    /**
     * Render a template file
     *
     * @param string $templateFile
     * @param array  $data
     *
     * @return string
     * @throws NotFoundException
     */
    public function render(string $templateFile, array $data = []): string
    {
        return $this->template->render($templateFile, $data);
    }
}
