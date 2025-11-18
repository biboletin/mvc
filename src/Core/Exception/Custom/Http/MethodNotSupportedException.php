<?php

namespace Bibo\Mvc\Core\Exception\Custom\Http;

use Bibo\Mvc\Core\Exception\AppException;

class MethodNotSupportedException extends AppException
{
    /**
     * HTTP status code for Method not supported
     *
     * @var int
     */
    protected $code = 405;

    /**
     * Default error message for Method not supported
     *
     * @var string
     */
    protected $message = 'Method not supported';
}
