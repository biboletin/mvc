<?php

namespace Bibo\Mvc\Core\Exception\Custom\Http;

use Bibo\Mvc\Core\Exception\AppException;

class MethodNotAllowedException extends AppException
{
    /**
     * HTTP status code for Method not allowed
     *
     * @var int
     */
    protected $code = 405;

    /**
     * Default error message for Method not allowed
     *
     * @var string
     */
    protected $message = 'Method not allowed';
}
