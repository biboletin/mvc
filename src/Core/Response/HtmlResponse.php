<?php

namespace Bibo\Mvc\Core\Response;

use Bibo\Mvc\Core\Request\Stream;

/**
 * Handles HTML responses
 */
class HtmlResponse extends BaseResponse
{
    /**
     * Constructor
     *
     * @param string $html HTML content
     * @param int $statusCode HTTP status code
     * @param array $headers Additional headers
     */
    public function __construct(string $html, int $statusCode = 200, array $headers = [])
    {
        $headers['Content-Type'] = ['text/html'];

        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write($html);
        $body->rewind();

        parent::__construct($statusCode, $headers, $body);
    }

    /**
     * Returns a new instance with updated HTML content
     *
     * @param string $html
     *
     * @return $this
     */
    public function withHtml(string $html): static
    {
        $body = new Stream(fopen('php://temp', 'r+'));
        $body->write($html);
        $body->rewind();

        return $this->withBody($body);
    }

    public function send(): void
    {
        $emitter = new ResponseEmitter();
        $emitter->emit($this);
    }
}
