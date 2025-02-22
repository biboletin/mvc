<?php

namespace Bibo\Core\Ajax;

use Bibo\Core\Interfaces\AjaxInterface;

class Ajax implements AjaxInterface
{
    public function __construct()
    {
        $this->handle();
    }

    public function handle(): void
    {
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            $this->$action();
        }
    }
}
