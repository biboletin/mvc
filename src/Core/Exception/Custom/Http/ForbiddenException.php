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
class ForbiddenException extends AppException
{
    /**
     * HTTP status code for Forbidden
     *
     * @var int
     */
    protected $code = 403;

    /**
     * Default error message for Forbidden
     *
     * @var string
     */
    protected $message = 'Forbidden';
}
