<?php

namespace Biboletin\Mvc;

use Biboletin\Mvc\Core\BaseRouter;
use Biboletin\Mvc\Core\Exceptions\InvalidHttpMethodException;

/**
 * Router class
 */
class Router extends BaseRouter
{
    /**
     * Router constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set get route
     *
     * @param string $path
     * @param array  $class
     *
     * @return void
     */
    public function get(string $path, array $class): void
    {
        $this->set(BaseRouter::GET_METHOD, $path, $class);
    }

    /**
     * Set post route
     *
     * @param string $path
     * @param array  $class
     *
     * @return void
     */
    public function post(string $path = '', array $class = []): void
    {
        $this->set(BaseRouter::POST_METHOD, $path, $class);
    }

    /**
     * Set put route
     *
     * @param string $path
     * @param array  $class
     *
     * @return void
     * @throws InvalidHttpMethodException
     */
    public function put(string $path = '', array $class = []): void
    {
        $this->set(BaseRouter::PUT_METHOD, $path, $class);
    }

    /**
     * Set delete route
     *
     * @param string $path
     * @param array  $class
     *
     * @return void
     * @throws InvalidHttpMethodException
     */
    public function delete(string $path = '', array $class = []): void
    {
        $this->set(BaseRouter::DELETE_METHOD, $path, $class);
    }
}
