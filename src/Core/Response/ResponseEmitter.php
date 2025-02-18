<?php

namespace Bibo\Core\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Emits HTTP responses
 */
class ResponseEmitter
{
    public function emit(ResponseInterface $response): void
    {
        // Send status line
        header(sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));

        // Send headers
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header($name . ': ' . $value, false);
            }
        }

        // Stream large body responses
        $body = $response->getBody();
        $body->rewind();
        while (!$body->eof()) {
            echo $body->read(8192);
            flush();
        }
    }
}
