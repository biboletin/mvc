<?php

namespace Bibo\Mvc\Core\Exception\Custom\Http;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\AppException;
use Bibo\Mvc\Core\Response\HtmlResponse;
use Bibo\Mvc\Core\Response\JsonResponse;
use Bibo\Mvc\Core\Response\ResponseEmitter;
use JsonException;
use Throwable;

/**
 * ResponseExceptionHandler
 * This class is responsible for handling exceptions and returning a response.
 */
class ResponseExceptionHandler extends AppException
{
    /**
     * Handle the exception
     */
    public static function handle(Throwable $exception): void
    {
        $code = $exception->getCode() ?: HttpStatus::InternalServerError->value;
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';

        try {
            if (str_contains($accept, 'application/json')) {
                $response = new JsonResponse([
                    'error' => true,
                    'message' => $exception->getMessage(),
                    'code' => $code,
                ], $code);
            } else {
                $response = new HtmlResponse(
                    self::formatHtmlError($exception, $code),
                    $code
                );
            }
        } catch (JsonException $e) {
            $response = new HtmlResponse(
                self::formatHtmlError($e, HttpStatus::InternalServerError->value),
                HttpStatus::InternalServerError->value
            );
        }

        $emitter = new ResponseEmitter();
        $emitter->emit($response);
    }

    private static function formatHtmlError(Throwable $e, int $code): string
    {
        return sprintf(
            '<h1>Error %d</h1><p>%s</p><pre>%s</pre>',
            $code,
            htmlspecialchars($e->getMessage(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            htmlspecialchars($e->getTraceAsString(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        );
    }
}
