<?php

namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;
use Bibo\Core\Response\JsonResponse;
use JsonException;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Index controller
 */
class IndexController extends Controller
{
    /**
     * Index
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function index(): string
    {
        $data = [
            'title' => 'Welcome',
            'header' => 'My site',
            'user' => 'John Doe',
            'price' => 1234.56,
            'items' => [
                'Item 1',
                'Item 2',
                'Item 3',
            ],
            'year' => date('Y')
        ];

        return $this->render('home', $data);
    }

    /**
     * About
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function about(): string
    {
        return $this->render('about', []);
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
