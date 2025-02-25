<?php

namespace Bibo\Core\Exception;

/**
 * Not found exception
 */
class NotFoundException extends AppException
{
    /**
     * Code
     *
     * @var int
     */
    protected $code = 404;
    /**
     * Message
     *
     * @var string
     */
    protected $message = 'The requested resource was not found!';
}
