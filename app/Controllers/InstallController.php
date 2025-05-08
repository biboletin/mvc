<?php

namespace Bibo\App\Controllers;

use Bibo\Core\Controller\Controller;

class InstallController extends Controller
{
    public function index(): string
    {
        return $this->render('install');
    }
}
