<?php

declare(strict_types=1);

namespace Bibo\App\Controllers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Enums\CurlStrategyType;
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
        $data = [
            'title' => 'Home Page',
            'name'  => 'Bibo Framework',
        ];

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

        $json = json_decode((string) $response->getBody(), true, 512, JSON_PRETTY_PRINT);

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

    public function single(): string
    {
        $client = new HttpClient(CurlStrategyType::SINGLE);
        $client
            ->setBaseUrl(config('api.placeholder.url'))
            ->setHeaders(config('api.placeholder.headers'))
            ->setTimeout(config('api.placeholder.timeout'));

        $response = $client->get('https://jsonplaceholder.typicode.com/posts/1');

        $json = json_decode($response->getBody(), true);

        dd(
            $response,
            $json
        );
        $data = [];

        return $this->view('home', $data);
    }

    public function multi(): string
    {
        $multi = new HttpClient(CurlStrategyType::MULTI);
        $request1 = new HttpClient(CurlStrategyType::SINGLE);
// dd(
//     get_class_methods($multi),
//     $multi,
//     get_class_methods($request1),
//     $request1,
// );
        $request1
            ->setBaseUrl(config('api.placeholder.url'))
            ->setHeaders(config('api.placeholder.headers'))
            ->setTimeout(config('api.placeholder.timeout'));

        $request2 = new HttpClient(CurlStrategyType::SINGLE);
        $request2
            ->setBaseUrl(config('api.placeholder.url'))
            ->setHeaders(config('api.placeholder.headers'))
            ->setTimeout(config('api.placeholder.timeout'));

        $multi
            ->addRequest($request1->get('https://jsonplaceholder.typicode.com/posts/1'))
            ->addRequest($request2->get('https://jsonplaceholder.typicode.com/posts/2'));

        $responses = $multi->executeAll();
        dd(
            $responses
        );
        $data = [];

        return $this->view('home', $data);
    }
}
