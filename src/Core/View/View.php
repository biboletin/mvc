<?php

namespace Bibo\Core\View;

use Bibo\Core\Template\Template;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\CacheInterface;

/**
 *
 */
class View
{
    /**
     * @var Template|mixed
     */
    protected Template $template;
    /**
     * @var CacheInterface|mixed
     */
    protected CacheInterface $cache;
    /**
     * @var bool
     */
    protected bool $enableCache;

    /**
     * @param ContainerInterface $container
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->cache = $container->get('file_cache');
        $this->cache->setPath('app/');
        $this->template = $container->get('template');
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function assign(string $key, mixed $value): void
    {
        $this->template->assign($key, $value);
    }

    /**
     * @param string $templateFile
     * @param array  $data
     *
     * @return string
     */
    public function render(string $templateFile, array $data = []): string
    {
        return $this->template->render($templateFile, $data);
    }
}
