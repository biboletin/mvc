<?php

namespace Bibo\App\Controllers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Rest\HttpClient;
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
        $client = new HttpClient();
        $response = $client->get('https://jsonplaceholder.typicode.com/posts/3');
        $json = json_decode($response->getBody(), true);
// dd($json);
        $data = [];

        return $this->render('home', $data);
    }

    /**
     * About
     *
     * @return string
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
        $client = new HttpClient();
        $response = $client->get('https://jsonplaceholder.typicode.com/posts/3');

        $json = json_decode($response->getBody(), JSON_PRETTY_PRINT);

        return new JsonResponse($json);
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
