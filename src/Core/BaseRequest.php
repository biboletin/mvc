<?php

namespace Biboletin\Mvc\Core;

use Biboletin\Mvc\Router;
use Biboletin\Mvc\Traits\HttpTrait;

/**
 * Base request class
 */
class BaseRequest
{
    use HttpTrait;

    /**
     * Global $_GET variable
     *
     * @var array<string>
     */
    private array $get;
    /**
     * Global $_POST variable
     *
     * @var array<string>
     */
    private array $post;
    /**
     * Global $_SERVER variable
     *
     * @var array<string>
     */
    private array $server;
    /**
     * Global $_SESSION variable
     *
     * @var array<string>
     */
    private array $session;
    /**
     * Global $_FILES variable
     *
     * @var array<string>
     */
    private array $files;

    /**
     * Global $_REQUEST variable
     *
     * @var array<string>
     */
    private array $bag;


    protected Router $router;

    /**
     * BaseRequest constructor.
     */
    public function __construct(Router $router)
    {
        $this->get = sanitizeGlobalVariable($_GET ?? []);
        $this->post = sanitizeGlobalVariable($_POST ?? []);
        $this->server = sanitizeGlobalVariable($_SERVER ?? []);
        $this->session = sanitizeGlobalVariable($_SESSION ?? []);
        $this->files = sanitizeGlobalVariable($_FILES ?? []);
        $this->bag = array_merge_recursive(
            $this->get,
            $this->post,
            $this->server,
            $this->session,
            $this->files
        );
        $this->router = $router;
    }

    /**
     * Check if key exists in the array
     *
     * @param string $key
     * @param array<string>  $method
     *
     * @return bool
     */
    private function keyExists(string $key, array $method): bool
    {
        return array_key_exists($key, $method);
    }

    /**
     * Get the value of the key from the $_GET array
     *
     * @param string $param
     *
     * @return string|null
     */
    public function get(string $param): ?string
    {
        if ($this->keyExists($param, $this->get) === false) {
            return null;
        }
        return $this->get[$param];
    }

    /**
     * Get the value of the key from the $_POST array
     *
     * @param string $param
     *
     * @return string|null
     */
    public function input(string $param): ?string
    {
        if ($this->keyExists($param, $this->post) === false) {
            return null;
        }
        return $this->post[$param];
    }

    /**
     * Get the value of the key from the $_SERVER array
     *
     * @param string $param
     *
     * @return string|null
     */
    public function bag(string $param): ?string
    {
        if ($this->keyExists($param, $this->bag) === false) {
            return null;
        }
        dd(123, $this->bag);
        return $this->bag[$param];
    }

    /**
     * Get request method
     *
     * @return string
     */
    public function method(): string
    {
        return $this->bag['REQUEST_METHOD'];
    }

    protected function server(string $key): string
    {
        $param = strtoupper($key);
        return $this->server[$param] ?? '';
    }

    /**
     * Parse the URL
     *
     * @return array
     */
    protected function parseUrl(): array
    {
        $url = $this->get('url');
        dd($url);
        return [];
    }
}
