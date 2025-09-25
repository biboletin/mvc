<?php

namespace Bibo\App\Controllers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Request\BaseRequest;
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
     * @param BaseRequest $request
     *
     * @return string
     * @throws NotFoundException
     */
    public function index(BaseRequest $request): string
    {
        $client = new HttpClient();
        $response = $client->get('https://jsonplaceholder.typicode.com/posts/3');
        $json = json_decode($response->getBody(), true);
// dd($json, $request);
        $data = [];

        return $this->view('home', $data);
    }

    /**
     * About
     *
     * @return string
     * @throws NotFoundException
     */
    public function about(): string
    {
        return $this->view('about', []);
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
    public function api(): JsonResponse
    {
        $client = new HttpClient();
        $response = $client->get('https://jsonplaceholder.typicode.com/posts/3');

        $json = json_decode($response->getBody(), JSON_PRETTY_PRINT);

        // return new JsonResponse($json);
        $this->json($json);
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
