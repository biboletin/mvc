<?php

namespace Bibo\Mvc\Core\Response;

use Bibo\Mvc\Core\Request\Stream;
use JsonException;
use JsonSerializable;

/**
 * HTTP response that serializes payloads to JSON.
 *
 * Provides convenience for encoding data to JSON and ensures the appropriate
 * Content-Type header is set. The body is stored in a temporary stream.
 */
class JsonResponse extends BaseResponse
{
    /**
     * Create a new JSON response with the given payload.
     *
     * @param mixed $data       The data to encode as JSON. JsonSerializable objects are supported.
     * @param int   $statusCode HTTP status code (default 200).
     * @param array $headers    Additional headers to include in the response.
     *
     * @throws JsonException If encoding fails.
     */
    public function __construct($data = null, int $statusCode = 200, array $headers = [])
    {
        if ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }

        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        $body->rewind();

        $headers['Content-Type'] = ['application/json'];

        parent::__construct($statusCode, $headers, $body);
    }

    /**
     * Set the response payload and re-encode to JSON.
     *
     * @param mixed $data The data to encode as JSON. JsonSerializable objects are supported.
     *
     * @return self New instance with an updated body stream.
     * @throws JsonException If encoding fails.
     */
    public function setData($data): self
    {
        if ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize(); // Serialize if it's a JsonSerializable object
        }

        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        $body->rewind();

        return $this->withBody($body);
    }

    /**
     * Emit the response to the client using the default ResponseEmitter.
     *
     * @return void
     */
    public function send(): void
    {
        $emitter = new ResponseEmitter();
        $emitter->emit($this);
    }
}
