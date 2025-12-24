<?php

namespace Bibo\Mvc\Core\Abstracts;

use Bibo\Mvc\Core\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

abstract class AbstractController
{
    protected View $view;

    protected ContainerInterface $container;

    /**
     * Controller constructor.
     *
     * @param ContainerInterface $container
     *
     * @return void
     *
     * @throws NotFoundExceptionInterface If no entry is found for the identifier.
     * @throws ContainerExceptionInterface If resolving the entry fails.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get(View::class);
    }
}
