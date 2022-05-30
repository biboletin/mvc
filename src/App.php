<?php

namespace Biboletin\Mvc;

use Dotenv\Dotenv;
use Biboletin\Mvc\Core\Application;

class App extends Application
{
    private function config()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        return true;
    }

    public function run()
    {
        $this->config();
    }
}
