<?php

namespace Biboletin\Mvc\Core\Gateway;

class AbstractGateway implements GatewayInterface
{
    private string $gateway;

    public function __construct()
    {
    }

    public function __destruct()
    {
        $this->gateway = '';
    }
}
