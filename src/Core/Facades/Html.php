<?php

namespace Bibo\Mvc\Core\Facades;

use Bibo\Mvc\Core\Html\HtmlHandler;

class Html
{
    private static HtmlHandler $instance;

    public static function getInstance(): HtmlHandler
    {
        return self::$instance;
    }

    public static function setInstance(HtmlHandler $instance): void
    {
        self::$instance = $instance;
    }

    public static function script(string $src, ?array $attributes = null): string
    {
        return self::getInstance()->script($src, $attributes);
    }

    public static function style(string $href, array $attributes = []): string
    {
        return self::getInstance()->style($href, $attributes);
    }


    public static function form(string $name, string $action, string $method, ?array $attributes = null): string
    {
        return self::getInstance()->form($name, $action, $method, $attributes);
    }

    public static function img(string $src, array $attributes = []): string
    {
        return self::getInstance()->img($src, $attributes);
    }

    public static function input(string $type = 'text', ?string $value = null, array $attributes = []): string
    {
        return self::getInstance()->input($type, $value, $attributes);
    }

    public static function textarea(string $text, array $attributes): string
    {
        return self::getInstance()->textarea($text, $attributes);
    }

    public static function select(array $options, array $attributes): string
    {
        return self::getInstance()->select($options, $attributes);
    }

    public static function csrf(): string
    {
        return self::getInstance()->csrf();
    }
}
