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
     *
     * @throws JsonException
     */
    public static function handle(Throwable $exception): void
    {
        $code = (int) $exception->getCode() ?: HttpStatus::InternalServerError->value;
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';

        if (str_contains($accept, 'application/json')) {
            $response = new JsonResponse([
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => $code,
            ], $code);
        } else {
            // Basic HTML error template or a full render if you have a view engine
            $html = "<h1>Error {$code}</h1><p>{$exception->getMessage()}</p>";
            $html .= "<pre>{$exception->getTraceAsString()}</pre>";
            $response = new HtmlResponse($html, $code);
        }
        $emitter = new ResponseEmitter();
        $emitter->emit($response);
    }
}
