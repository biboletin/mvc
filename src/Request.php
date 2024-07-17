<?php

namespace Biboletin\Mvc;

use Biboletin\Mvc\Core\BaseRequest;
use Biboletin\Mvc\Traits\HttpTrait;

class Request extends BaseRequest
{
    use HttpTrait;

    public function resolve(): void
    {
//        dd('resolve');
        // Get the request path and method
        $url = $this->server('REQUEST_URI') ?? null;
        $parts = explode('/', $url);
        dd($url, $parts, self::CONTROLLERS_NAMESPACE);
//
//        $namespace = 'App\\Controllers\\';
//        $controller = $namespace . 'IndexController';
//        $method = 'index';
//        $params = [];
//        call_user_func([new $controller(), $method], ...$params);
    }
}
