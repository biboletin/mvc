<?php

namespace Bibo\Core\Exception;

/**
 * Unauthorized access exception handler
 */
class UnauthorizedException extends AppException
{
    /**
     * Code
     *
     * @var int
     */
    protected $code = 401;
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'Unauthorized access!';
}
