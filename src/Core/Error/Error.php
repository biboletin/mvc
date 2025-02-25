<?php

namespace Bibo\Core\Error;

use Bibo\Core\Exception\AppException;
use ErrorException;
use Throwable;

/**
 * Application error handler
 */
class Error
{
    /**
     * Register error handler
     *
     * @return void
     */
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Handle exceptions
     *
     * @param Throwable $exception
     *
     * @return void
     */
    public static function handleException(Throwable $exception): void
    {
        // $code = $exception instanceof AppException ? $exception->getCode() : 500;
        $code = $exception->getCode();

        http_response_code($code);

        if (self::isJsonRequest()) {
            self::renderJsonError($exception);
        } else {
            self::renderErrorPage($code);
        }
    }

    /**
     * Handle errors
     *
     * @throws ErrorException
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
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
    public static function handleShutdown(): void
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
    private static function renderJsonError(Throwable $exception): void
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
     * @param int $code
     *
     * @return void
     */
    private static function renderErrorPage(int $code): void
    {
        $errorPage = VIEW_PATH . 'error/' . $code . '.php';

        if (!file_exists($errorPage)) {
            $errorPage = VIEW_PATH . 'error/500.php';
        }

        include $errorPage;
    }
}
