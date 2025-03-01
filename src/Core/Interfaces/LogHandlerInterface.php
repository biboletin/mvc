<?php

namespace Bibo\Core\Interfaces;

interface LogHandlerInterface
{
    public function write(string $level, string $message, array $context = []): void;
}
