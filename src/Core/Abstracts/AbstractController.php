<?php

namespace Bibo\Mvc\Core\Abstracts;

use Bibo\Mvc\Core\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class AbstractController
{
    protected ?View $view = null;

    protected ?ContainerInterface $container = null;

    /**
     * Controller constructor.
     */
    public function __construct(?ContainerInterface $container = null)
    {
        try {
            $this->view = $container->get(View::class);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            echo $e->getMessage();
        }
        $this->container = $container;
    }
}
