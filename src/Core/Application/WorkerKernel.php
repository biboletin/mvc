<?php

namespace Bibo\Mvc\Core\Application;

use Bibo\Mvc\Core\Interfaces\KernelInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Worker Kernel
 */
class WorkerKernel implements KernelInterface
{
    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Implement handle() method.
    }

    /**
     * Terminates a request.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return void Always returns null.
     */
    public function terminate(ServerRequestInterface $request, ResponseInterface $response): void
    {
        // TODO: Implement terminate() method.
    }
}
