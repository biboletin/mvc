<?php

namespace Bibo\Mvc\Core\Database;

class QueryBuilder
{
    private string $sql;

    public function __construct()
    {
    }

    public function __invoke()
    {
    }
    public function __toString()
    {
        return $this->sql;
    }
    public function __destruct()
    {
    }
}
