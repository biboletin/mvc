<?php

namespace Bibo\Mvc\Core\Error;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\AppException;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Rest\Message\Response;
use Bibo\Mvc\Core\View\View;
use ErrorException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class ErrorResponseFactory
{
    /**
     * The view instance.
     *
     * @var View|null
     */
    private ?View $view;

    /**
     * Debug mode
     *
     * @var bool
     */
    private bool $debug;

    /**
     * Set the view instance.
     *
     * @param View|null $view
     *
     * @return void
     */
    public function setView(?View $view = null): void
    {
        $this->view = $view;
    }

    /**
     * Get the view instance.
     *
     * @return View|null
     */
    public function getView(): ?View
    {
        return $this->view;
    }

    /**
     * Check debug mode
     *
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Set debug mode
     *
     * @param bool $debug
     *
     * @return void
     */
    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    /**
     * Create a new error response.
     *
     * @param array $errorData
     * @param ServerRequestInterface $request
     * @param int $status
     *
     * @return ResponseInterface
     *
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

    /**
     * Create a new error response from an exception.
     *
     * @param Throwable $exception
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws NotFoundException
     */
    public function createFromException(Throwable $exception, ServerRequestInterface $request): ResponseInterface
    {
        if ($exception instanceof AppException) {
            $status = $exception->getCode() ?: HttpStatus::BadRequest->value;
            $errorData = [
                'type' => 'Application',
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        } elseif ($exception instanceof ErrorException) {
            $status = HttpStatus::InternalServerError->value;
            $errorData = [
                'type' => 'Core',
                'message' => $exception->getMessage(),
                'severity' => $exception->getSeverity(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        } else {
            $status = HttpStatus::InternalServerError->value;
            $errorData = [
                'type' => 'Unknown',
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        return $this->createResponse($errorData, $request, $status);
    }

    /**
     * Create a new JSON response.
     *
     * @param array $errorData
     * @param int $status
     *
     * @return ResponseInterface
     */
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

    /**
     * Create a new text response.
     *
     * @param array $errorData
     * @param int $status
     *
     * @return ResponseInterface
     */
    private function createTextResponse(array $errorData, int $status): ResponseInterface
    {
        $response = new Response();
        $response = $response->withStatus($status)
            ->withHeader('Content-Type', 'text/plain');

        $text = $this->debug
            ? $this->buildPlainText($errorData)
            : ($errorData['message'] ?? 'An internal server error occurred.');

        $response->getBody()->write($text);

        return $response;
    }

    /**
     * Create a new HTML response.
     *
     * @param array $errorData
     * @param int $status
     *
     * @return ResponseInterface
     *
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

    /**
     * Build plain text error message from error data.
     *
     * @param array $data
     *
     * @return string
     */
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

        return $data['message'] ?? 'An internal server error occurred.';
    }
}
