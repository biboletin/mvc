<?php

namespace Biboletin\Mvc\Traits;

trait HttpTrait
{
    /**
     * Controllers namespace
     *
     * @var string
     */
    private const CONTROLLERS_NAMESPACE = '\\App\\Controllers\\';
    /**
     * Default controller
     *
     * @var string
     */
    private const DEFAULT_CONTROLLER = 'DefaultController';
    /**
     * Default method
     *
     * @var string
     */
    private const DEFAULT_CONTROLLER_METHOD = 'index';

    /**
     * Get method
     */
    protected const GET_METHOD = 'get';
    /**
     * Post method
     */
    protected const POST_METHOD = 'post';
    /**
     * Put method
     */
    protected const PUT_METHOD = 'put';
    /**
     * Delete method
     */
    protected const DELETE_METHOD = 'delete';

    /***/
    /**
     * Request method
     *
     * @var string
     */
    private string $method = 'GET';

    /**
     * Request host
     *
     * @var string
     */
    private string $host = 'localhost';

    /**
     * Http scheme
     *
     * @var string
     */
    private string $scheme = 'http';

    /**
     * Http port
     *
     * @var string
     */
    private string $port = '80';

    /**
     * Request path
     *
     * @var string
     */
    private string $path = '/';

    /**
     * Request query
     *
     * @var string
     */
    private string $query = '';

    /**
     * Request fragment
     *
     * @var string
     */
    private string $url = '';

    /**
     * IP address of the client
     *
     * @var string
     */
    private string $ip = '';
}
