<?php

namespace Bibo\App\Middleware;

use Bibo\Mvc\Core\Abstracts\AbstractMiddleware;
use Bibo\Mvc\Core\Interfaces\MiddlewareInterface;
use Bibo\Mvc\Core\Session\SessionHandler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware extends AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     *
     * @throws ContainerExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = $this->container->get(SessionHandler::class);
        $request = $request->withAttribute('session', $session);

        return $handler->handle($request);
    }

    /**
     * @inheritDoc
     */
    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        $this->next = $middleware;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getNext(): ?MiddlewareInterface
    {
        return $this->next;
    }

    /**
     * @inheritDoc
     */
    public function handle(): void
    {
    }

    /**
     * @inheritDoc
     */
    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    /**
     * @inheritDoc
     */
    public function clearNext(): void
    {
        $this->next = null;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'class'   => static::class,
            'hasNext' => $this->hasNext(),
        ];
    }
}
