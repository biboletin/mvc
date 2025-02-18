<?php

namespace Bibo\Core\Response;

use Bibo\Core\Response\BaseResponse;

class RedirectResponse extends BaseResponse
{
    public function __construct(string $url, int $statusCode = 200, array $headers = [])
    {
        $headers['Location'] = [$url];

        parent::__construct($statusCode, $headers, null);
    }
}
