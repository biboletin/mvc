<?php

namespace Bibo\Core\View;

abstract class View
{
    protected static array $data = [];

    public function __construct(?array $data = null)
    {
        if ($data) {
            self::$data = $data;
        }
    }

    public function render(): array
    {
        return $this->data;
    }

    public function __get($key)
    {
        return self::$data[$key];
    }

    public function __set($key, $value)
    {
        self::$data[$key] = $value;
    }

    public function __isset($key)
    {
        return isset(self::$data[$key]);
    }

    public function __unset($key)
    {
        unset(self::$data[$key]);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __invoke(): array
    {
        return $this->render();
    }

    public function __call($method, $args)
    {
        if (isset(self::$data[$method]) && is_callable(self::$data[$method])) {
            return call_user_func_array(self::$data[$method], $args);
        }
    }

    public static function __callStatic($method, $args)
    {
        if (isset(self::$data[$method]) && is_callable(self::$data[$method])) {
            return call_user_func_array(self::$data[$method], $args);
        }
    }

    public function __debugInfo()
    {
        return self::$data;
    }

    public function __sleep()
    {
        return ['data'];
    }

    public function __wakeup()
    {
        // do nothing
    }

    public function __clone()
    {
        // do nothing
    }

    public function renderPartial($view, $data = [])
    {
        $view = new $view($data);
        return $view->render();
    }

    public function __destruct()
    {
        self::$data = [];
    }
}
