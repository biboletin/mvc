<?php

namespace Bibo\Mvc\Core\Exception\Custom\Http;

use Bibo\Mvc\Core\Exception\AppException;

/**
 * Class NotFoundException
 *
 * This exception is thrown when a requested resource is not found.
 *
 * @package Biboletin\Exceptions\Custom\Http
 */
class UnauthorizedException extends AppException
{
    /**
     * HTTP status code for Unauthorized
     *
     * @var int
     */
    protected $code = 401;

    /**
     * Default error message for Unauthorized
     *
     * @var string
     */
    protected $message = 'Unauthorized';
}
