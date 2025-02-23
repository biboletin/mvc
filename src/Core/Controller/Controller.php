<?php

namespace Bibo\Core\Controller;

use Bibo\Core\View\View;

/**
 * Controller class
 */
abstract class Controller
{
    private View $view;
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // TODO: Implement __construct() method.
        $this->view = new View();
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
        // TODO: Implement __destruct() method.
    }
}
