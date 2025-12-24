<?php

namespace Bibo\Mvc\Core\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface KernelInterface
{
    /**
     * Handles the incoming server request and provides an appropriate response.
     *
     * @param ServerRequestInterface $request The incoming server request.
     *
     * @return ResponseInterface The response generated from handling the request.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;

    /**
     * Terminates the ongoing request and sends the response to the client.
     *
     * @param ServerRequestInterface $request The incoming server request.
     * @param ResponseInterface $response The response to be sent to the client.
     *
     * @return void
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response): void;
}
