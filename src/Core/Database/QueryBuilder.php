<?php

namespace Bibo\Mvc\Core\Database;

use Bibo\Mvc\Core\Database\Contracts\PdoDriverInterface;

class QueryBuilder
{
    private PdoDriverInterface $driver;

    private string $table;

    private array $select = [];
    private array $where = [];
    private array $join = [];
    private array $groupBy = [];
    private array $having = [];
    private array $orderBy = [];
    private array $limit = [];

    private string $sql;

    public function __construct(?PdoDriverInterface $driver = null)
    {
        $this->driver = $driver;
    }

    public function select(array $columns = ['*']): self
    {
        $this->select = $columns;

        return $this;
    }

    public function from(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    public function where(string $condition, array $params = []): self
    {
        $this->where[] = [$condition, $params];

        return $this;
    }

    public function join(string $table, string $condition, array $params = []): self
    {
        $this->join[] = [$table, $condition, $params];

        return $this;
    }

    public function groupBy(string $column): self
    {
        $this->groupBy[] = $column;

        return $this;
    }

    public function orderBy(string $column, string $order = 'ASC'): self
    {
        $this->orderBy[] = [$column, $order];

        return $this;
    }

    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = [$limit, $offset];

        return $this;
    }

    public function __invoke()
    {
    }

    public function get(): array
    {
        $pdo = $this->driver->getPdo();

        $sql = 'SELECT ' . implode(', ', $this->select) . ' FROM ' . $this->table;


        $this->sql = $sql;

        $stmt = $pdo->prepare($this->__toString());
        $stmt->execute($this->where[0][1]);

        return $stmt->fetchAll();
    }

    public function raw(): string
    {
        return $this->sql ?? '';
    }
    public function __toString()
    {
        return $this->sql;
    }
    public function __destruct()
    {
        $this->sql = '';
    }
}
