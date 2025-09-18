<?php

namespace Bibo\Mvc\Core\Exception;

use Exception;
use Throwable;

class AppException extends Exception
{
    /**
     * Default error code
     *
     * @var int
     */
    protected $code = 500;

    /**
     * Default error message
     *
     * @var string
     */
    protected $message = 'Internal Server Error!';

    /**
     * Context data for the exception
     *
     * @var array
     */
    protected array $context = [];

    /**
     * Constructor
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     * @param array          $context
     */
    public function __construct(string $message = '', int $code = 500, ?Throwable $previous = null, array $context = [])
    {
        if (empty($message)) {
            $message = $this->message;
        }

        if (empty($code)) {
            $code = $this->code;
        }

        $this->context = $context;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the context data
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Get status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->code ?? 500;
    }
}
