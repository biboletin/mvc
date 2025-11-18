<?php

namespace Bibo\Mvc\Core\Adapters\Curl;

use Bibo\Mvc\Core\Rest\Message\Response;
use Bibo\Mvc\Core\Wrapper\Curl\CurlSingleWrapper;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CurlSingleAdapter implements ClientInterface
{
    protected CurlSingleWrapper $client;

    public function __construct(?CurlSingleWrapper $client = null)
    {
        $this->client = $client;
    }

    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface The response received.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->client->setUrl((string) $request->getUri());
        $this->client->setMethod($request->getMethod());
        $this->client->setHeaders($request->getHeaders());

        $body = (string) $request->getBody();
        if (!empty($body)) {
            $this->client->setPostFields($body);
        }
        $result = $this->client->execute();
        $status = curl_getinfo($this->client->getHandle(), CURLINFO_HTTP_CODE);

        return new Response($status, [], $result);
    }
}
