<?php

namespace Bibo\Core\Error;

use Bibo\Core\Enum\HttpStatus;
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
     * @throws NotFoundException
     */
    public function handleException(Throwable $exception): void
    {
        if (ob_get_length()) {
            ob_clean();
        }

        $code = $exception->getCode();

        // Try to match with HttpStatus or fallback to 500
        $status = HttpStatus::tryFrom($code) ?? HttpStatus::InternalServerError;
        http_response_code($status->value);

        if (self::isJsonRequest()) {
            $this->renderJsonError($exception, $status);
        } else {
            $this->renderErrorPage($exception, $status);
        }

        ob_end_flush();
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
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Shutdown function
     *
     * @return void
     * @throws NotFoundException
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
            E_USER_NOTICE,
            E_RECOVERABLE_ERROR,
            E_DEPRECATED,
            E_USER_DEPRECATED,
            E_NOTICE,
            E_ALL,
        ];

        if ($error !== null && in_array($error['type'], $errorTypes)) {
            self::handleException(
                new ErrorException(
                    $error['message'],
                    500,
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
    private function renderJsonError(Throwable $exception, HttpStatus $status): void
    {
        echo json_encode(
            [
                'error' => true,
                'message' => $exception->getMessage(),
                'code' => $status->value,
            ],
            JSON_PRETTY_PRINT
        );
    }

    /**
     * Renders html error page
     *
     * @param Throwable  $exception
     * @param HttpStatus $status
     *
     * @return void
     * @throws NotFoundException
     */
    private function renderErrorPage(Throwable $exception, HttpStatus $status): void
    {
        $template = 'error/error';

        echo $this->template->render($template, [
            'code' => $status->value,
            'message' => $status->message(),
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
        exit;
    }
}
