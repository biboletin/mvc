<?php

namespace Bibo\Mvc\Core\Error;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Rest\Message\Response;
use Bibo\Mvc\Core\View\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorResponseFactory
{
    private ?View $view;
    private bool $debug;

    public function __construct(?View $view = null, bool $debug = false)
    {
        $this->view = $view;
        $this->debug = $debug;
    }

    /**
     * @throws NotFoundException
     */
    public function createResponse(
        array $errorData,
        ServerRequestInterface $request,
        int $status = HttpStatus::InternalServerError->value
    ): ResponseInterface {
        $accept = $request->getHeaderLine('Accept');

        if (str_contains($accept, 'application/json')) {
            return $this->createJsonResponse($errorData, $status);
        }

        if (str_contains($accept, 'text/html') && $this->view !== null) {
            return $this->createHtmlResponse($errorData, $status);
        }

        return $this->createTextResponse($errorData, $status);
    }

    private function createJsonResponse(array $errorData, int $status): ResponseInterface
    {
        $response = new Response();
        $response = $response->withStatus($status)
            ->withHeader('Content-Type', 'application/json');

        $payload = $this->debug
            ? $errorData
            : ['error' => $errorData['message'] ?? 'An unexpected error occurred'];

        $response->getBody()->write(
            json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        return $response;
    }

    private function createTextResponse(array $errorData, int $status): ResponseInterface
    {
        $response = new Response();
        $response = $response->withStatus($status)
            ->withHeader('Content-Type', 'text/plain');

        $text = $this->debug
            ? $this->buildPlainText($errorData)
            : ($errorData['message'] ?? "An internal server error occurred.");

        $response->getBody()->write($text);

        return $response;
    }

    /**
     * @throws NotFoundException
     */
    private function createHtmlResponse(array $errorData, int $status): ResponseInterface
    {
        $response = new Response();
        $response = $response->withStatus($status)
            ->withHeader('Content-Type', 'text/html');

        $template = $this->debug ? 'error/dev' : 'error/prod';
        $http = HttpStatus::resolve($status);

        $html = $this->view->render($template, [
            'title' => $http->category(),
            'status' => $status,
            'error' => $errorData,
        ]);

        $response->getBody()->write($html);

        return $response;
    }

    private function buildPlainText(array $data): string
    {
        if (isset($data['trace'])) {
            return sprintf(
                "%s: %s in %s on line %d\n%s",
                $data['type'] ?? 'Error',
                $data['message'] ?? 'Unknown error',
                $data['file'] ?? 'unknown',
                $data['line'] ?? 0,
                implode("\n", $data['trace'])
            );
        }

        return $data['message'] ?? "An internal server error occurred.";
    }
}
