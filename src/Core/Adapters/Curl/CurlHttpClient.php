<?php

namespace Bibo\Mvc\Core\Adapters\Curl;

use Bibo\Mvc\Core\Rest\Message\Response;
use Bibo\Mvc\Core\Wrapper\Curl\CurlSingleWrapper;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurlHttpClient implements ClientInterface
{
    protected CurlSingleWrapper $client;

    public function __construct(CurlSingleWrapper $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->client
            ->setUrl((string) $request->getUri())
            ->setMethod($request->getMethod())
            ->setHeaders($request->getHeaders())
            ->setPostFields((string) $request->getBody());

        $result = $this->client->execute();

        return new Response($result['status'], $result['headers'], $result['body']);
    }
}
