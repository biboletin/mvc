<?php

namespace Bibo\Mvc\Core\Error;

use ErrorException;
use Psr\Log\LoggerInterface;
use Throwable;

class Error
{
    protected LoggerInterface $logger;
    protected string $environment;

    public function __construct(LoggerInterface $logger, string $environment = 'production')
    {
        $this->logger = $logger;
        $this->environment = $environment;
    }

    /**
     * Register global handlers (optional in middleware setups)
     */
    public function register(): void
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * Convert PHP errors into exceptions
     *
     * @throws ErrorException
     */
    public function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $level)) {
            return false;
        }
        throw new ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Handle uncaught exceptions (log only)
     */
    public function handleException(Throwable $exception): void
    {
        $this->logger->error($this->formatThrowable($exception));
    }

    /**
     * Handle shutdown errors (fatal errors)
     *
     * @return void
     */
    public function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], $this->getFatalErrorTypes(), true)) {
            $message = "{$error['message']} in {$error['file']} on line {$error['line']}";
            $this->logger->error($message);
        }
    }

    /**
     * Normalize an exception into array format for API responses
     *
     * @param Throwable $e
     *
     * @return array
     */
    public function normalize(Throwable $e): array
    {
        return [
            'error' => true,
            'type' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ];
    }

    /**
     * Format exception for logging (string
     *
     * @param Throwable $e
     *
     * @return string
     */
    public function formatThrowable(Throwable $e): string
    {
        return sprintf(
            "%s: %s in %s on line %d\n%s",
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );
    }

    /**
     * Fatal error types
     *
     * @return array
     */
    private function getFatalErrorTypes(): array
    {
        return [
            E_ERROR,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_USER_ERROR,
        ];
    }
}
