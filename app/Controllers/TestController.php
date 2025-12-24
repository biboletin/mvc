<?php

namespace Bibo\App\Controllers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Response\JsonResponse;
use JsonException;

class TestController extends Controller
{
    /**
     * Ping action
     *
     * @throws JsonException
     */
    public function ping(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'success',
            'message' => 'pong',
        ]);
    }
}
