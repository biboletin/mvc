<?php

namespace Biboletin\Mvc\Core;

/**
 * Base request class
 */
class BaseRequest
{
    /**
     * @var array
     */
    private array $get;
    /**
     * @var array
     */
    private array $post;
    /**
     * @var array
     */
    private array $server;
    /**
     * @var array
     */
    private array $session;
    /**
     * @var array
     */
    private array $cookie;
    /**
     * @var string
     */
    private string $method;
    /**
     * IP address of the client
     *
     * @var string
     */
    private string $ip;

    /**
     *
     */
    public function __construct()
    {
        $this->get = sanitizeGlobalVariable($_GET);
        $this->post = sanitizeGlobalVariable($_POST);
        $this->server = sanitizeGlobalVariable($_SERVER);
        $this->session = sanitizeGlobalVariable($_SESSION);
        $this->cookie = sanitizeGlobalVariable($_COOKIE);
        $this->ip = $this->server['REMOTE_ADDR'];
    }

    protected function ip()
    {
        return $this->ip;
    }
}
