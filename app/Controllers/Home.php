<?php

namespace App\Controllers;

use App\Models\UserModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \Datetime;

use ReflectionException;

class Home extends BaseController
{
    public function index()
    {
        $model = new UserModel();
       

        return view('welcome_message' );
    }


    public function get()
    {

        return ('welcome_message');
    }
}
