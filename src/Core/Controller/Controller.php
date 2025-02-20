<?php

namespace Bibo\Core\Controller;

/**
 * Controller class
 */
abstract class Controller
{
    /**
     * Controller constructor.
     */
    public function __construct()
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Render a view
     *
     * @param string $view
     * @param array  $data
     *
     * @return void
     */
    public function render(string $view, array $data = []): void
    {
        // TODO: Implement render() method.
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