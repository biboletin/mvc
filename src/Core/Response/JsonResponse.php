<?php

namespace Bibo\Mvc\Core\Response;

use Bibo\Mvc\Core\Request\Stream;
use JsonException;
use JsonSerializable;

/**
 * Handles JSON responses
 */
class JsonResponse extends BaseResponse
{
    /**
     * Constructor
     *
     * @param mixed $data
     * @param int   $statusCode
     * @param array $headers
     *
     * @throws JsonException
     */
    public function __construct($data = null, int $statusCode = 200, array $headers = [])
    {
        if ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }

        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
        $body->rewind();


        $headers['Content-Type'] = 'application/json';

        parent::__construct($statusCode, $headers, $body);
    }

    /**
     * Set the data of the JSON response and re-encode it
     *
     * @param $data
     *
     * @return self
     * @throws JsonException
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
     * Send the JSON response
     *
     * @return void
     */
    public function send(): void
    {
        http_response_code($this->getStatusCode()); // Ensure proper HTTP status

        foreach ($this->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, true);
            }
        }

        echo $this->getBody()->getContents();
    }
}
