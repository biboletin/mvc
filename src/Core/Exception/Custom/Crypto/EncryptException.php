<?php

namespace Bibo\Mvc\Core\Exception\Custom\Crypto;

use Bibo\Mvc\Core\Exception\AppException;

/**
 * * Class EncryptException
 *
 * This exception is thrown when there is an error during encryption operations.
 * It extends the base AppException class.
 */
class EncryptException extends AppException
{
    /**
     * The HTTP status code for this exception.
     *
     * @var int
     */
    protected $code = 500;

    /**
     * The error message for this exception.
     *
     * @var string
     */
    protected $message = 'Encryption Error';
}
