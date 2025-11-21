<?php

namespace Bibo\Mvc\Core\Database;

class Sql
{
    private string $sql;
    private array $params;
    private string $type;
    private string $table;
    private string $alias;
    private string $schema;
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
