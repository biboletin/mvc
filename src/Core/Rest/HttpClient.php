<?php

namespace Bibo\Mvc\Core\Rest;

use Bibo\Mvc\Core\Enum\CurlStrategyType;
use Bibo\Mvc\Core\Rest\Message\Request;
use Bibo\Mvc\Core\Rest\Message\Response;
use Bibo\Mvc\Core\Rest\Message\Stream;
use Bibo\Mvc\Core\Rest\Message\Uri;
use Bibo\Mvc\Core\Wrapper\Curl\CurlStrategyFactory;
use Bibo\Mvc\Core\Wrapper\CurlWrapper;
use RuntimeException;

/**
 * HttpClient class for making HTTP requests.
 *
 * This class provides a high-level API for making HTTP requests using the CurlWrapper.
 * It supports all standard HTTP methods (GET, POST, PUT, DELETE, PATCH, HEAD, OPTIONS, TRACE, CONNECT)
 * and handles request/response conversion between PSR-7 compatible objects.
 */
class HttpClient
{
    /**
     * The cURL client used to make HTTP requests.
     *
     * @var CurlWrapper|null
     */
    protected ?CurlWrapper $client = null;

    /**
     * Base URL for all requests.
     *
     * @var string
     */
    protected string $baseUrl = '';

    /**
     * Initialize a new HttpClient instance.
     *
     * Creates a new CurlWrapper instance with a 'single' mode for making individual HTTP requests.
     */
    public function __construct()
    {
        // $strategy = new CurlStrategyFactory();
        // $strategy->create(CurlStrategyType::SINGLE);

        // $this->client = new CurlWrapper();
        $this->client = new CurlWrapper(CurlStrategyFactory::create(CurlStrategyType::SINGLE));
    }

    /**
     * Send an HTTP request and return the response.
     *
     * This is the main method for sending requests. It takes a Request object,
     * executes it using the underlying cURL client, and returns a Response object.
     * The method handles the conversion between PSR-7 compatible request/response objects
     * and the underlying cURL implementation.
     *
     * @param Request $request The request object containing method, URI, headers, and body
     *
     * @return Response The response object containing status code, headers, and body
     */
    public function send(Request $request): Response
    {
        $response = $this->executeRequest($request);

        // Assuming $response is a response object with a body (Stream)
        $stream = $response->getBody();

        // Now, you can use the stream to get the contents:
        $content = $stream->getContents();

        // Alternatively, convert the stream directly to a string
        // $content = (string) $stream;

        // Return the response with the content
        return $response;
    }

    /**
     * Execute an HTTP request using the cURL client.
     *
     * This private method handles the low-level details of converting a Request object
     * to a cURL request, executing it, and converting the result to a Response object.
     * It flattens the headers from the Request object to the format expected by cURL,
     * sets the URL and headers on the cURL client, executes the request, and creates
     * a Response object from the result.
     *
     * @param Request $request The request object to execute
     *
     * @return Response The response object created from the cURL response
     *
     * @throws RuntimeException If the request fails to execute
     */
    private function executeRequest(Request $request): Response
    {
        $headers = [];
        foreach ($request->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                $headers[] = $name . ': ' . $value;  // Flatten header array to "Name: value"
            }
        }
        $headers[] = 'X-Request-ID: ' . uniqid('', true); // Add a unique request ID header
        $this->client->setUrl($request->getUri()->__toString());
        $this->client->setHeaders($headers);
        $response = $this->client->execute();

        // Create and return the Response object
        return new Response($this->client->getStatusCode(), [], $response, null);
    }

    /**
     * Send an HTTP GET request.
     *
     * This method creates and sends a GET request to the specified URL with optional headers.
     * GET requests are used to retrieve data from a server without modifying any resources.
     * According to HTTP specifications, GET requests should be idempotent and safe.
     *
     * @param string $url     The URL to send the request to
     * @param array  $headers Optional array of headers to include in the request
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function get(string $url, array $headers = []): Response
    {
        $this->baseUrl = $url;

        $uri = new Uri($url);
        $body = new Stream(''); // empty body for GET
        $request = new Request('GET', $uri, $headers, $body);
        $this->client->setOption(CURLOPT_HTTPGET, true);

        return $this->send($request);
    }

    /**
     * Send an HTTP POST request.
     *
     * This method creates and sends a POST request to the specified URL with optional headers and data.
     * POST requests are used to submit data to be processed to a specified resource,
     * often causing a change in state or side effects on the server.
     *
     * @param string     $url     The URL to send the request to
     * @param array      $headers Optional array of headers to include in the request
     * @param mixed|null $data    Optional data to send in the request body (string, array, etc.)
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function post(string $url, array $headers = [], $data = null): Response
    {
        $uri = new Uri($url);
        $body = new Stream($data); // body for POST
        $request = new Request('POST', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_POST, true);
        $this->client->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->send($request);
    }

    /**
     * Send an HTTP PUT request.
     *
     * This method creates and sends a PUT request to the specified URL with optional headers and data.
     * PUT requests are used to update a resource or create it if it doesn't exist at the specified URL.
     * According to HTTP specifications, PUT requests should be idempotent - multiple identical requests
     * should have the same effect as a single request.
     *
     * @param string     $url     The URL to send the request to
     * @param array      $headers Optional array of headers to include in the request
     * @param mixed|null $data    Optional data to send in the request body (string, array, etc.)
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function put(string $url, array $headers = [], $data = null): Response
    {
        $uri = new Uri($url);
        $body = new Stream($data); // body for PUT
        $request = new Request('PUT', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->client->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->send($request);
    }

    /**
     * Send an HTTP DELETE request.
     *
     * This method creates and sends a DELETE request to the specified URL with optional headers and data.
     * DELETE requests are used to request the removal of a resource at the specified URL.
     * According to HTTP specifications, DELETE requests should be idempotent - multiple identical requests
     * should have the same effect as a single request.
     *
     * Note: While DELETE requests typically don't include a body, this method allows sending data
     * in case it's needed for certain APIs.
     *
     * @param string     $url     The URL to send the request to
     * @param array      $headers Optional array of headers to include in the request
     * @param mixed|null $data    Optional data to send in the request body (string, array, etc.)
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function delete(string $url, array $headers = [], $data = null): Response
    {
        $uri = new Uri($url);
        $body = new Stream(''); // empty body for DELETE
        $request = new Request('DELETE', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $this->client->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->send($request);
    }

    /**
     * Send an HTTP PATCH request.
     *
     * This method creates and sends a PATCH request to the specified URL with optional headers and data.
     * PATCH requests are used to apply partial modifications to a resource.
     * Unlike PUT which replaces the entire resource, PATCH applies a set of changes described in the request.
     * PATCH is not necessarily idempotent, meaning successive identical PATCH requests may have different effects.
     *
     * @param string     $url     The URL to send the request to
     * @param array      $headers Optional array of headers to include in the request
     * @param mixed|null $data    Optional data to send in the request body (string, array, etc.)
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function patch(string $url, array $headers = [], $data = null): Response
    {
        $uri = new Uri($url);
        $body = new Stream($data); // body for PATCH
        $request = new Request('PATCH', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'PATCH');
        $this->client->setOption(CURLOPT_POSTFIELDS, $data);

        return $this->send($request);
    }

    /**
     * Send an HTTP HEAD request.
     *
     * This method creates and sends a HEAD request to the specified URL with optional headers.
     * HEAD requests are identical to GET requests except that the server MUST NOT return a message body
     * in the response. HEAD is useful for retrieving meta-information about a resource without
     * transferring the resource itself (e.g., checking if a resource exists, when it was last modified).
     *
     * According to HTTP specifications, HEAD requests should be idempotent and safe.
     *
     * @param string $url     The URL to send the request to
     * @param array  $headers Optional array of headers to include in the request
     *
     * @return Response The response from the server (without body content)
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function head(string $url, array $headers = []): Response
    {
        $uri = new Uri($url);
        $body = new Stream(''); // empty body for HEAD
        $request = new Request('HEAD', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_NOBODY, true);
        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'HEAD');

        return $this->send($request);
    }

    /**
     * Send an HTTP OPTIONS request.
     *
     * This method creates and sends an OPTIONS request to the specified URL with optional headers.
     * OPTIONS requests are used to describe the communication options for the target resource.
     * This method can be used to determine which HTTP methods are supported by a server,
     * or to check for CORS (Cross-Origin Resource Sharing) support.
     *
     * The response typically includes an Allow header that lists the supported methods,
     * and for CORS requests, it may include Access-Control-* headers.
     *
     * @param string $url     The URL to send the request to
     * @param array  $headers Optional array of headers to include in the request
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function options(string $url, array $headers = []): Response
    {
        $uri = new Uri($url);
        $body = new Stream(''); // empty body for OPTIONS
        $request = new Request('OPTIONS', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'OPTIONS');

        return $this->send($request);
    }

    /**
     * Send an HTTP TRACE request.
     *
     * This method creates and sends a TRACE request to the specified URL with optional headers.
     * TRACE requests are used for diagnostic purposes - the server should echo back the received
     * request so that a client can see what intermediate servers are adding or changing in the request.
     *
     * Note: TRACE is often disabled on production servers for security reasons (to prevent
     * cross-site tracing attacks), so this method may not work with all servers.
     *
     * @param string $url     The URL to send the request to
     * @param array  $headers Optional array of headers to include in the request
     *
     * @return Response The response from the server (should contain the request as received by the server)
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function trace(string $url, array $headers = []): Response
    {
        $uri = new Uri($url);
        $body = new Stream(''); // empty body for TRACE
        $request = new Request('TRACE', $uri, $headers, $body);

        $this->client->setOption(CURLOPT_CUSTOMREQUEST, 'TRACE');

        return $this->send($request);
    }

    /**
     * Send an HTTP CONNECT request.
     *
     * This method creates and sends a CONNECT request to the specified URL with optional headers.
     * CONNECT is primarily used to establish a network connection to a resource (usually for HTTPS through a proxy).
     * It starts two-way communications with the requested resource and can be used to open a tunnel.
     *
     * Note: CONNECT is typically used by clients to establish a tunnel through a proxy server,
     * often for secure HTTPS connections. This is a specialized HTTP method that may not be
     * supported by all servers or may have limited use cases outside of proxy connections.
     *
     * @param string $url     The URL to send the request to (typically in the format hostname:port)
     * @param array  $headers Optional array of headers to include in the request
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function connect(string $url, array $headers = []): Response
    {
        $uri = new Uri($url);
        $body = new Stream(''); // empty body for CONNECT
        $request = new Request('CONNECT', $uri, $headers, $body);

        return $this->send($request);
    }

    /**
     * Send a ping request to the specified URL.
     *
     * This method sends a simple GET request to the specified URL, typically used to check if the server is reachable.
     * It can be used for health checks or to verify connectivity without expecting any specific response body.
     *
     * @param string $url     The URL to send the ping request to
     * @param array  $headers Optional array of headers to include in the request
     *
     * @return Response The response from the server
     *
     * @throws RuntimeException If the request fails to execute
     */
    public function ping(string $url, array $headers = []): Response
    {
        return $this->send(new Request('GET', new Uri($url), $headers, new Stream('')));
    }

    /**
     * Get the base URL for all requests.
     *
     * This method returns the base URL that is used for all requests made by this HttpClient instance.
     * The base URL can be set during construction or modified later.
     *
     * @return string The base URL
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Set the base URL for all requests.
     *
     * This method allows setting a base URL that will be used
     * for all subsequent requests made by this HttpClient instance.
     * It can be useful for APIs where all endpoints share a common base URL.
     *
     * @param string $baseUrl The base URL to set
     */
    public function setBaseUrl(string $baseUrl): void
    {
        $this->baseUrl = rtrim($baseUrl, '/'); // Ensure no trailing slash
    }

    /**
     * Get the underlying cURL client.
     *
     * This method returns the CurlWrapper instance used by this HttpClient.
     * It can be useful for accessing lower-level cURL options or methods directly.
     *
     * @return CurlWrapper|null The cURL client instance, or null if not set
     */
    public function getClient(): ?CurlWrapper
    {
        return $this->client;
    }

    /**
     * Clean up resources when the HttpClient is destroyed.
     *
     * This method is called automatically when the HttpClient instance is no longer needed.
     * It sets the cURL client to null and clears the base URL to free up resources.
     */
    public function __destruct()
    {
        $this->client = null;
        $this->baseUrl = '';
    }
}
