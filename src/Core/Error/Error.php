<?php

namespace Bibo\Mvc\Core\Error;

use Bibo\Mvc\Core\Enums\HttpStatus;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Template\Template;
use ErrorException;
use Psr\Log\LoggerInterface;
use Throwable;

class Error
{
    /**
     * Error class to handle errors and exceptions in a PHP application.
     *
     * This class registers custom error and exception handlers, logs errors,
     * and displays error messages based on the environment (development or production).
     *
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * The environment in which the application is running.
     * Can be 'development' or 'production'.
     *
     * @var string
     */
    protected string $environment;

    /**
     * The path to the error template file for displaying errors.
     * If not set, a default error message will be displayed.
     *
     * @var Template
     */
    protected Template $errorTemplate;

    /**
     * Error constructor.
     *
     * @param LoggerInterface $logger      The logger instance to log errors.
     * @param string          $environment The environment in which the application is running (default: 'production').
     */
    public function __construct(LoggerInterface $logger, string $environment = 'production')
    {
        $this->logger = $logger;
        $this->environment = $environment;
    }

    /**
     * Sets the error template for displaying errors.
     *
     * @param Template $errorTemplate The path to the error template file.
     */
    public function setErrorTemplate(Template $errorTemplate): void
    {
        $this->errorTemplate = $errorTemplate;
    }

    /**
     * Registers the error and exception handlers.
     *
     * This method sets up the custom error handler, exception handler,
     * and shutdown function to handle errors and exceptions gracefully.
     */
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);

        ini_set('display_errors', $this->isDev() ? '1' : '0');
        ini_set('log_errors', '1');
        error_reporting(E_ALL);
    }

    /**
     * Handles PHP errors.
     * This method converts PHP errors into ErrorException instances
     * and throws them for consistent handling.
     *
     * @param int $level The level of the error raised.
     * This can be one of the E_* constants.
     *
     * @param string $message The error message.
     * @param string $file The filename where the error occurred.
     * @param int $line The line number where the error occurred.
     *
     * @return bool Returns false if the error is not handled, true otherwise.
     *
     * @throws ErrorException If the error is handled, it throws an ErrorException.
     */
    public function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $level)) {
            return false;
        }

        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Handles uncaught exceptions.
     * This method logs the exception and displays an error message
     * based on the environment (development or production).
     *
     * @param Throwable $exception The uncaught exception to handle.
     *                             This can be any Throwable instance,
     *                             including ErrorException.
     *
     * @throws Throwable Rethrows the exception if needed.
     *
     * @return void
     */
    public function handleException(Throwable $exception): void
    {
        $this->logger->error($this->formatThrowable($exception));

        if ($this->wantsJson()) {
            $this->jsonResponse($exception);
        } else {
            $this->htmlResponse($exception);
        }
    }

    /**
     * Handles shutdown events.
     * This method checks for fatal errors that occurred during script execution
     * and logs them. It also displays an error message based on the environment.
     *
     * This method is called when the script execution ends,
     * allowing for cleanup and final error handling.
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
            $message = "{$error['message']} in {$error['file']} on line {$error['line']}";
            $this->logger->error($message);

            if ($this->isDev()) {
                echo $this->errorTemplate->render('error/error', [
                    'code' => $error['type'],
                    'message' => $message,
                    'exception' => '',
                    'trace' => '',
                ]);
            } else {
                if ($this->wantsJson()) {
                    $this->jsonResponse(null, $message);
                } else {
                    $this->htmlResponse(null, $message);
                }
            }
        }
    }

    /**
     * Checks if the application is running in development mode.
     *
     * @return bool Returns true if the environment is 'development', false otherwise.
     */
    protected function isDev(): bool
    {
        return $this->environment === 'development';
    }

    /**
     * Checks if the request expects a JSON response.
     *
     * This method checks the 'Accept' header to determine if the client
     * expects a JSON response.
     *
     * @return bool Returns true if the request expects JSON, false otherwise.
     */
    protected function wantsJson(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) &&
            str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
    }

    /**
     * Sends a JSON response for errors.
     *
     * This method formats the error message as JSON and sends it to the client.
     *
     * @param Throwable|null $e
     * @param string|null    $fatal
     *
     * @return void
     */
    protected function jsonResponse(?Throwable $e = null, ?string $fatal = null): void
    {
        http_response_code(HttpStatus::InternalServerError->value);
        header('Content-Type: application/json');

        $response = ['error' => true];

        if ($this->isDev()) {
            if ($e) {
                $response['exception'] = [
                    'type'    => get_class($e),
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'trace'   => explode("\n", $e->getTraceAsString()),
                ];
            } elseif ($fatal) {
                $response['fatal'] = $fatal;
            }
        } else {
            $response['message'] = 'An internal server error occurred.';
        }

        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Sends an HTML response for errors.
     *
     * This method formats the error message as HTML and sends it to the client.
     *
     * @param Throwable|null $e
     * @param string|null    $fatal
     *
     * @return void
     * @throws NotFoundException
     */
    protected function htmlResponse(?Throwable $e = null, ?string $fatal = null): void
    {
        header('Content-Type: text/html');
        http_response_code(HttpStatus::InternalServerError->value);

        if ($this->isDev()) {
            if ($e) {
                echo $this->formatExceptionHtml($e);
            } elseif ($fatal) {
                echo "<pre>$fatal</pre>";
            }
        } else {
            echo $this->errorTemplate->render('error/error', [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Formats a Throwable instance into a string for logging.
     *
     * This method is used to create a consistent log message format
     * for exceptions and errors.
     *
     * @param Throwable $e The Throwable instance to format.
     *
     * @return string The formatted error message.
     */
    private function formatThrowable(Throwable $e): string
    {
        return sprintf(
            "[%s] %s: %s in %s on line %d\n%s",
            date('Y-m-d H:i:s'),
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    /**
     * Formats an exception into an HTML string for display.
     *
     * This method is used to create a detailed HTML representation
     * of an exception for development environments.
     *
     * @param Throwable $e The Throwable instance to format.
     *
     * @return string The formatted HTML string.
     */
    protected function formatExceptionHtml(Throwable $e): string
    {
        return '<pre>' .
            get_class($e) . ': ' . $e->getMessage() . "\n" .
            'In ' . $e->getFile() . ' on line ' . $e->getLine() . "\n" .
            "Stack trace:\n" . $e->getTraceAsString() .
            '</pre>';
    }
}
