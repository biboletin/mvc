<?php

namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;
use Bibo\Core\Response\HtmlResponse;
use Bibo\Core\Response\JsonResponse;
use Bibo\Core\View\View;
use JsonException;

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

    public function about(): string
    {
        return 'About';
    }

    public function contacts(): string
    {
        return 'Contacts';
    }

    /**
     * @throws JsonException
     */
    public function json(): JsonResponse
    {
        return new JsonResponse(['message' => 'JSON']);
    }
}
