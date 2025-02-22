<?php

namespace Bibo\Core\Template;

abstract class Template
{
    protected string $template;
    protected array $data = [];

    public function __construct(string $template)
    {
        $this->template = $template;
    }

    public function assign($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function render(): false|string
    {
        extract($this->data);
        ob_start();
        include $this->template;
        return ob_get_clean();
    }
}
