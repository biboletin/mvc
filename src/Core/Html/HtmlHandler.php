<?php

namespace Bibo\Mvc\Core\Html;

use Bibo\Mvc\Core\Input\InputHandler;

class HtmlHandler
{
    private string $html;
    private ?InputHandler $input = null;

    public function __construct(InputHandler $input)
    {
        $this->input = $input;
    }

    public function script(string $src, ?array $attributes = null): string
    {
        $base = PUBLIC_PATH . '';
        return '';
    }

    public function style(string $href, array $attributes = []): string
    {
        return '';
    }


    public function form(string $name, string $action, string $method, ?array $attributes = null): string
    {
        return '';
    }

    public function img(string $src, array $attributes = []): string
    {
        return '';
    }

    public function input(string $type = 'text', ?string $value = null, array $attributes = []): string
    {
        return '';
    }

    public function textarea(string $text, array $attributes): string
    {
        return '';
    }

    public function select(array $options, array $attributes): string
    {
        return '';
    }

    public function csrf(): string
    {
        return '';
    }

    public function __destruct()
    {
        $this->html = '';
        $this->input = null;
    }
}
