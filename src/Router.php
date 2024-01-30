<?php

namespace Biboletin\Mvc;

use Biboletin\Mvc\Core\BaseRouter;
use Biboletin\Mvc\Core\Exceptions\InvalidHttpMethodException;

/**
 *
 */
class Router extends BaseRouter
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $path
     * @param array  $class
     *
     * @return void
     */
    public function get(string $path = '', array $class = []): void
    {
        try {
            $this->set(BaseRouter::GET_METHOD, $path, $class);
        } catch (InvalidHttpMethodException $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @param string $path
     * @param array  $class
     *
     * @return void
     * @throws InvalidHttpMethodException
     */
    public function post(string $path = '', array $class = []): void
    {
        $this->set(BaseRouter::POST_METHOD, $path, $class);
    }

    /**
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
