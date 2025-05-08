<?php

namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;
use Bibo\Core\Response\JsonResponse;
use JsonException;

class TestController extends Controller
{
    /**
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