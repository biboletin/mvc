<?php

namespace Bibo\App\Controllers;

use Bibo\Mvc\Core\Controller\Controller;
use Bibo\Mvc\Core\Exception\Custom\Http\NotFoundException;

class InstallController extends Controller
{
    /**
     * Index action
     *
     * @return string
     *
     * @throws NotFoundException
     */
    public function index(): string
    {
        return $this->view('install');
    }
}
