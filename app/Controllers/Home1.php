<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('header');
        return view('sidebar');
        return view('content1');
        return view('footer');
       
    }
}
