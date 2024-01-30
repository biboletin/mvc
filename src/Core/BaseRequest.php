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
        $this->get = sanitizeGlobalVariable($_GET ?? []);
        $this->post = sanitizeGlobalVariable($_POST ?? []);
        $this->server = sanitizeGlobalVariable($_SERVER ?? []);
        $this->session = sanitizeGlobalVariable($_SESSION ?? []);
        $this->cookie = sanitizeGlobalVariable($_COOKIE ?? []);
    }

    private function keyExists(string $key, array $method): bool
    {
        if (empty($key)) {
            return false;
        }
        if (!array_key_exists($key, $method)) {
            return false;
        }
        return true;
    }
    public function get(string $param): ?string
    {
        if ($this->keyExists($param, $this->get) === false) {
            return null;
        }
        return $this->get[$param];
    }

    public function input(string $param): ?string
    {
        if ($this->keyExists($param, $this->post) === false) {
            return null;
        }
        return $this->post[$param];
    }
}
