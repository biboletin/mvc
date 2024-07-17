<?php

namespace App\Controllers;

use Biboletin\Mvc\Controller;

class DefaultController extends Controller
{
    public function index(): void
    {
        echo 'Default controller, index page';
    }
}
