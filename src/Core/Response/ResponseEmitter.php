<?php

namespace Bibo\Mvc\Core\Response;

use Bibo\Mvc\Core\Enums\ResponseBufferSize;
use Psr\Http\Message\ResponseInterface;

/**
 * Emits a PSR-7 ResponseInterface to the browser.
 *
 * This utility sends the status line, headers, and streams the body in chunks
 * to avoid memory spikes for large payloads.
 */
class ResponseEmitter
{
    /**
     * Output the given response to the client.
     *
     * Note: This method uses native header() and echo/flush() calls and should
     * be invoked before any output has been sent.
     *
     * @param ResponseInterface $response The HTTP response to emit.
     *
     * @return void
     */
    public function emit(ResponseInterface $response): void
    {
        if (PHP_SAPI === 'cli') {
            return;
        }

        if (headers_sent()) {
            return;
        }

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

        if ($body->isSeekable()) {
            $body->rewind();
        }

        while (!$body->eof()) {
            echo $body->read(8192);
            flush();
        }
    }
}
