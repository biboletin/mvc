<?php

namespace Bibo\Core\Response;

use Bibo\Core\Request\Stream;
use Bibo\Core\Response\BaseResponse;
use Psr\Http\Message\StreamInterface;
use JsonSerializable;

/**
 * Handles JSON responses
 */
class JsonResponseHandler extends BaseResponse
{
    /**
     * Constructor
     *
     * @param mixed                     $data
     * @param int                  $statusCode
     * @param array                $headers
     * @param StreamInterface|null $body
     */
    public function __construct($data = null, int $statusCode = 200, array $headers = [], ?StreamInterface $body = null)
    {
        if ($body === null) {
            if ($data instanceof JsonSerializable) {
                $data = $data->jsonSerialize();
            }
            $body = new Stream(fopen('php://temp', 'r+'));
            $body->write(json_encode($data));
            $body->rewind();
        }

        $headers['Content-Type'] = ['application/json'];

        parent::__construct($statusCode, $headers, $body);
    }

    /**
     * Set the data of the JSON response and re-encode it
     *
     * @param $data
     *
     * @return self
     */
    public function setData($data): self
    {
        if ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize(); // Serialize if it's a JsonSerializable object
        }

        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write(json_encode($data));
        $body->rewind();

        return $this->withBody($body);
    }

    public function send(): void
    {
        foreach ($this->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, true);
            }
        }

        echo $this->getBody()->getContents();
    }
}
