<?php

namespace Bibo\Mvc\Core\Error;

use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;
use Bibo\Mvc\Core\Logger\Logger;
use Bibo\Mvc\Core\Request\BaseRequest;
use Bibo\Mvc\Core\Response\ResponseEmitter;
use ErrorException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class Error
{
    /**
     * @var LoggerInterface|mixed
     */
    protected LoggerInterface $logger;

    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get(Logger::class);
        $this->container = $container;
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
     *
     * @throws NotFoundException
     */
    public function handleException(Throwable $exception): void
    {
        $this->logger->error($this->formatThrowable($exception));

        $factory = null;
        $emitter = null;
        $request = null;

        try {
            $factory = $this->container->get(ErrorResponseFactory::class);
            $emitter = $this->container->get(ResponseEmitter::class);
            $request = $this->container->get(BaseRequest::class);
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            $exception = $e;
        }

        $response = $factory->createFromException($exception, $request);
        $emitter->emit($response);
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
