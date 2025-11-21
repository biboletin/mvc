<?php

namespace Bibo\Mvc\Core\Abstracts;

use Bibo\Mvc\Core\Error\Error;
use Bibo\Mvc\Core\Error\ErrorResponseFactory;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Bibo\Mvc\Core\Logger\LogManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractMiddleware
{
    protected ContainerInterface $container;

    protected LoggerInterface $logger;

    /**
     * Error handler
     *
     * @var Error|mixed
     */
    protected Error $errorHandler;

    /**
     * Error response
     *
     * @var ErrorResponseFactory|mixed
     */
    protected ErrorResponseFactory $responseFactory;

    /**
     * Next middleware
     *
     * @var MiddlewareInterface|null
     */
    protected ?MiddlewareInterface $next = null;

    /**
     * Initialize error handling and error response
     *
     * @param ContainerInterface $container
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get(LogManager::class)->get('security');
        $this->errorHandler = $container->get(Error::class);
        $this->responseFactory = $container->get(ErrorResponseFactory::class);
    }
}
