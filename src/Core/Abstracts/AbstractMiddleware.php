<?php

namespace Bibo\Mvc\Core\Abstracts;

use Bibo\Mvc\Core\Error\Error;
use Bibo\Mvc\Core\Error\ErrorResponseFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AbstractMiddleware
{
    protected ContainerInterface $container;
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
        $this->errorHandler = $container->get(Error::class);
        $this->responseFactory = $container->get(ErrorResponseFactory::class);
    }
}
