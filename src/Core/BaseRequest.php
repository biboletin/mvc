<?php

namespace Biboletin\Mvc\Core;

use Psr\Http\Message\RequestInterface;
use Biboletin\Mvc\Core\Http\BaseRequestTrait;
use Biboletin\Mvc\Core\Http\BaseMessageTrait;

use function strtolower;

class BaseRequest implements RequestInterface
{
    use BaseRequestTrait;
    use BaseMessageTrait;

    public function __construct(
        string $method = '',
        $uri = '',
        array $headers = [],
        $body = null,
        string $version = '1.1'
    ) {
        $this->method = strtolower($method);
        $this->version = $version;
        $this->setUri($uri);
        $this->setHeaders($headers);
        $this->setBody($body);
    }
}
