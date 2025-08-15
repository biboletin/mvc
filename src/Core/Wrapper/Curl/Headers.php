<?php

namespace Bibo\Mvc\Core\Wrapper\Curl;

class Headers
{
    protected array $headers = [];

    public function __construct(array $headers = [])
    {
        foreach ($headers as $name => $value) {
            $this->set($name, $value);
        }
    }

    public function set(string $name, string|array $value): void
    {
        $normalized = strtolower($name);
        $this->headers[$normalized] = [
            'original' => $name,
            'values'   => is_array($value) ? array_values($value) : [$value],
        ];
    }

    public function add(string $name, string|array $value): void
    {
        $normalized = strtolower($name);
        if (!isset($this->headers[$normalized])) {
            $this->set($name, $value);
            return;
        }

        $values = is_array($value) ? array_values($value) : [$value];
        $this->headers[$normalized]['values'] = array_merge(
            $this->headers[$normalized]['values'],
            $values
        );
    }

    public function get(string $name): array
    {
        $normalized = strtolower($name);
        return $this->headers[$normalized]['values'] ?? [];
    }

    public function has(string $name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function all(): array
    {
        $result = [];
        foreach ($this->headers as $data) {
            $result[$data['original']] = $data['values'];
        }
        return $result;
    }

    public function toArray(): array
    {
        return $this->all(); // alias
    }

    public function remove(string $name): void
    {
        unset($this->headers[strtolower($name)]);
    }
}
