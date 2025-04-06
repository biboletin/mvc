<?php

namespace Bibo\Core\Exception;

use Bibo\Core\Response\JsonResponse;
use Bibo\Core\Response\ResponseEmitter;
use JsonException;
use Throwable;

/**
 * ResponseExceptionHandler
 * This class is responsible for handling exceptions and returning a JSON response.
 * It extends the AppException class and provides a static method to handle exceptions.
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
        $code = $exception->getCode() ?: 500;

        $response = new JsonResponse(['error' => $exception->getMessage()], $code);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }
}
