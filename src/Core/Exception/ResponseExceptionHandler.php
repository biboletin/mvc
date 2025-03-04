<?php

namespace Bibo\Core\Exception;

use Bibo\Core\Response\JsonResponse;
use Bibo\Core\Response\ResponseEmitter;
use JsonException;
use Throwable;

class ResponseExceptionHandler extends AppException
{
    /**
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
