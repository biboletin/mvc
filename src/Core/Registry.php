<?php

namespace Biboletin\Mvc\Core;

final class Registry
{
    private array $registry;

    public function __construct()
    {
    }

    public function set(string $key, $value): void
    {
        $this->registry[$key] = $value;
    }

    public function get(string $key, $default = null)
    {
        if (!isset($this->registry[$key])) {
            return $default ?? null;
        }
        return $this->registry[$key];
    }

    public function __destruct()
    {
        $this->registry = [];
    }
}
