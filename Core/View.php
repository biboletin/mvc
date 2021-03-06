<?php

namespace Core;

/**
 * Class View
 *
 * @package Core
 */
final class View
{
    /**
     * Views base directory
     */
    private string $viewsDirectory;

    /**
     * View constructor.
     */
    public function __construct()
    {
        $this->viewsDirectory = __DIR__ . '/../App/Views/';
    }

    public function __destruct()
    {
        $this->viewsDirectory = '';
    }

    /**
     * @param array<string>   $params
     */

    /**
     * Set view
     * Delimiter .
     *
     * @param string $viewName page
     * @param array $params Data
     * @return string
     */
    public function set(string $viewName = 'error', array $params = []): string
    {
        $view = $this->parseView($viewName);
        $data = $params;
        ob_start();
        if (! file_exists($view . '.php')) {
            include $this->viewsDirectory . 'error.php';
        }
        if (file_exists($view . '.php')) {
            extract($data, EXTR_SKIP);
            include $view . '.php';
        }
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;
        exit;
    }

    private function parseView(string $view): string
    {
        return $this->viewsDirectory . implode('/', explode('.', $view));
    }
}
