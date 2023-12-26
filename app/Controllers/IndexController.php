<?php

namespace App\Controllers;

use Biboletin\Mvc\Controller;

class IndexController extends Controller
{
    public function home()
    {
        return 'Index';
    }

    public function contacts()
    {
        return 'Contacts';
    }
}
