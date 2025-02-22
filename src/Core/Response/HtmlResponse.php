<?php

namespace Bibo\Core\Response;

use Psr\Http\Message\ResponseInterface;
use Bibo\Core\Response\BaseResponse;
use Bibo\Core\Request\Stream;

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
}
