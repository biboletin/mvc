<?php

namespace Bibo\Mvc\Core\Traits\Http;

trait ServerHelper
{
    public function getServerParam(string $key, $default = null): mixed
    {
        $server = $this->getServerParams();

        return $server[$key] ?? $default;
    }

    public function getAllServerParams(): mixed
    {
        return $this->getServerParams();
    }
}
