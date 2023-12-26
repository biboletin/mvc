<?php

namespace Biboletin\Mvc\Core\Exceptions;

use Exception;
use Throwable;

class InvalidModelException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
