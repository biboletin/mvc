<?php

namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;
use Bibo\Core\Response\HtmlResponse;
use Bibo\Core\Response\JsonResponse;
use Bibo\Core\View\View;
use JsonException;

/**
 * Index controller
 */
class IndexController extends Controller
{
    /**
     * Index
     *
     * @return string
     */
    public function index(): string
    {
        return $this->render('index', []);
    }

    /**
     * About
     *
     * @return string
     */
    public function about(): string
    {
        return 'About';
    }

    /**
     * Contacts
     *
     * @return string
     */
    public function contacts(): string
    {
        return 'Contacts';
    }

    /**
     * Json
     *
     * @throws JsonException
     */
    public function json(): JsonResponse
    {
        return new JsonResponse(['message' => 'JSON']);
    }

    /**
     * User
     *
     * @param string $name
     * @param int    $id
     *
     * @return string
     */
    public function user(string $name, int $id): string
    {
        return 'Hello ' . $name . ' with id: ' . $id . ' from controller!';
    }
}
