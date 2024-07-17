<?php

namespace App\Controllers;

use Biboletin\Mvc\Controller;

class IndexController extends Controller
{
    public function index(): void
    {
        echo 'Index page';
    }

    public function contacts(): void
    {
        echo 'Contacts';
    }
}
