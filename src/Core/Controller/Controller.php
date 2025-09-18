<?php

namespace Bibo\Mvc\Core\Controller;

use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\View\View;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Controller class
 */
class Controller
{
    private ?View $view = null;

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

    /**
     * Render a view
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     * @throws NotFoundException
     */
    protected function render(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }

    protected function redirect(string $url): void
    {
        // TODO: Implement redirect() method.
    }

    /**
     * Controller destructor.
     */
    public function __destruct()
    {
        $this->view = null;
        $this->container = null;
    }
}
