<?php

namespace Bibo\Mvc\Core\Exception\Custom\Http;

use Bibo\Mvc\Core\Exception\AppException;

class TooManyRequestsException extends AppException
{
    /**
     * HTTP status code for Too many requests
     *
     * @var int
     */
    protected $code = 429;

    /**
     * Default error message for Too many requests
     *
     * @var string
     */
    protected $message = 'Too Many Requests';
}
