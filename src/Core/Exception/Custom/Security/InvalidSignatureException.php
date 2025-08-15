<?php

namespace Bibo\Mvc\Core\Exception\Custom\Security;

use Bibo\Mvc\Core\Exception\AppException;

/**
 * Class InvalidSignatureException
 *
 * This exception is thrown when a request has an invalid signature.
 *
 * @package Biboletin\Exceptions\Custom\Security
 */
class InvalidSignatureException extends AppException
{
    /**
     * HTTP status code for Unauthorized
     *
     * @var int
     */
    protected $code = 401;

    /**
     * Default error message for Invalid Signature
     *
     * @var string
     */
    protected $message = 'Invalid Signature';
}
