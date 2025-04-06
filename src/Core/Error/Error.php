<?php

namespace Bibo\Core\Error;

use Bibo\Core\Exception\NotFoundException;
use Bibo\Core\Template\Template;
use ErrorException;
use Throwable;

/**
 * Application error handler
 */
class Error
{
    private Template $template;

    private array $errors = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        511 => 'Network Authentication Required',
        520 => 'Unknown Error',
        521 => 'Web Server Is Down',
        522 => 'Connection Timed Out',
        523 => 'Origin Is Unreachable',
        524 => 'A Timeout Occurred',
        525 => 'SSL Handshake Failed',
        526 => 'Invalid SSL Certificate',
        527 => 'Railgun Error',
        530 => 'Site Is Frozen',
        598 => 'Network Read Timeout Error',
        599 => 'Network Connect Timeout Error',
    ];

    public function __construct()
    {
    }

    /**
     * Register error handler
     *
     * @return void
     */
    public function register(): void
    {
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function setTemplate(Template $template): void
    {
        $this->template = $template;
    }

    /**
     * Handle exceptions
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        $code = $exception->getCode();

        http_response_code($code);

        if (self::isJsonRequest()) {
            $this->renderJsonError($exception);
        } else {
            $this->renderErrorPage($exception, $code);
        }
    }

    /**
     * Handle errors
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     *
     * @return void
     * @throws ErrorException
     */
    public function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        if (!(error_reporting() & $errno)) {
            return;
        }

        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Shutdown function
     *
     * @return void
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();
        $errorTypes = [
            E_ERROR,
            E_WARNING,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
            E_USER_WARNING,
        ];

        if ($error !== null && in_array($error['type'], $errorTypes)) {
            self::handleException(
                new ErrorException(
                    $error['message'],
                    0,
                    $error['type'],
                    $error['file'],
                    $error['line']
                )
            );
        }
    }

    /**
     * If request is json
     *
     * @return bool
     */
    private static function isJsonRequest(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }

    /**
     * If request is json
     * echo json string
     *
     * @param Throwable $exception
     *
     * @return void
     */
    private function renderJsonError(Throwable $exception): void
    {
        echo json_encode(
            [
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Renders html error page
     *
     * @param Throwable $exception
     * @param int       $code
     *
     * @return void
     * @throws NotFoundException
     */
    private function renderErrorPage(Throwable $exception, int $code): void
    {
        $template = 'error/error';

        echo $this->template->render($template, [
            'code' => $code,
            'message' => $this->errors[$code],
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
        exit;
    }
}
