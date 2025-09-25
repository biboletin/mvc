<?php

namespace Bibo\App\Controllers;

use Bibo\Mvc\Core\Controller\Controller;

class InstallController extends Controller
{
    public function index(): string
    {
        return $this->view('install');
    }
}
