<?php

namespace Bibo\Core\Controller;

use Psr\Container\ContainerInterface;

/**
 * Controller class
 */
class Controller
{
    private $view;

    private ?ContainerInterface $container = null;

    /**
     * Controller constructor.
     */
    public function __construct(?ContainerInterface $container = null)
    {
        $this->view = $container->get('views');
        $this->container = $container;
    }

    /**
     * Render a view
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function render(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }

    public function redirect(string $url): void
    {
        // TODO: Implement redirect() method.
    }

    /**
     * Controller destructor.
     */
    public function __destruct()
    {
        $this->container = null;
    }
}
