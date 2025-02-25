<?php

namespace Bibo\Core\Exception;

use Exception;
use Throwable;

/**
 * Application exception handler
 */
class AppException extends Exception
{
    /**
     * Constructor
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
