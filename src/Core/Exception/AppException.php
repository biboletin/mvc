<?php

namespace Bibo\Core\Exception;

use Exception;
use Throwable;

/**
 * Application exception handler
 */
class AppException extends Exception
{
    protected $code = 500;

    protected $message = 'Internal Server Error!';
    /**
     * Constructor
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 404, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = $this->message;
        }

        if (empty($code)) {
            $code = $this->code;
        }

        if (empty($previous)) {
            $previous = $this;
        }

        parent::__construct($message, $code, $previous);
    }
}
