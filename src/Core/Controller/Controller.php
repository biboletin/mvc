<?php

namespace Bibo\Mvc\Core\Controller;

use Bibo\Mvc\Core\Abstracts\AbstractController;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Response\RedirectResponse;
use JsonException;
use Psr\Container\ContainerInterface;

/**
 * Base controller providing common helpers for rendering views and
 * returning JSON or redirect responses.
 */
class Controller extends AbstractController
{
    /**
     * Create a new controller instance.
     *
     * @param ContainerInterface|null $container Optional DI container.
     */
    public function __construct(?ContainerInterface $container = null)
    {
        parent::__construct($container);
    }

    /**
     * Render a view template with the provided data.
     *
     * @param string $view The view path/name.
     * @param array  $data Variables to pass to the view.
     *
     * @return string Rendered HTML.
     * @throws NotFoundException If the view cannot be located.
     */
    protected function view(string $view, array $data = []): string
    {
        return $this->view->render($view, $data);
    }

    /**
     * Create a JSON response with the given payload and optional status/headers.
     *
     * @param mixed      $data    Data to encode as JSON.
     * @param int|null   $status  HTTP status code (default 200).
     * @param array|null $headers Additional headers.
     *
     * @return JsonResponse
     * @throws JsonException If the payload cannot be encoded.
     */
    protected function json(mixed $data, ?int $status = 200, ?array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    /**
     * Create a redirect response to the provided URL.
     *
     * @param string $url     Target URL.
     * @param int    $status  HTTP status code (default 302).
     * @param array  $headers Additional headers.
     *
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302, array $headers = []): RedirectResponse
    {
        return new RedirectResponse($url, $status, $headers);
    }

    /**
     * Controller destructor: help GC by releasing references.
     */
    public function __destruct()
    {
        $this->view = null;
        $this->container = null;
    }
}
