<?php

namespace Bibo\Mvc\Core\Adapters\Curl;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Rest\Message\Response;
use Bibo\Mvc\Core\Wrapper\Curl\CurlMultiWrapper;
use Bibo\Mvc\Core\Wrapper\Curl\CurlSingleWrapper;
use Exception;

class CurlMultiAdapter
{
    /**
     * Curl Client
     *
     * @var CurlMultiWrapper
     */
    protected CurlMultiWrapper $client;

    /**
     * Constructor
     */
    public function __construct(CurlMultiWrapper $client)
    {
        $this->client = $client;
    }

    /**
     * Send requests
     *
     * @param array $requests
     *
     * @return array
     *
     * @throws Exception
     */
    public function sendRequests(array $requests): array
    {
        $wrappers = [];
        foreach ($requests as $request) {
            $wrapper = new CurlSingleWrapper();
            $wrapper
                ->setUrl((string)$request->getUri())
                ->setMethod($request->getMethod())
                ->setHeaders($request->getHeaders())
                ->setPostFields((string)$request->getBody());

            $wrappers[] = $wrapper;
        }

        $rawResponses = $this->client->executeAll();
        $responses = [];

        foreach ($rawResponses as $rawResponse) {
            $responses[] = new Response(HttpStatus::OK->value, [], $rawResponse);
        }

        return $responses;
    }
}
