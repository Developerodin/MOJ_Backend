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
        // $userCount = $this->$model->getUserCount();

        // echo "Total number of users: " . $userCount;

        return view('login' );
    }
    public function authenticate()
    {
        // Handle form submission and authentication
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $authenticated = $this->request->getPost('password');

        // Your authentication logic here
        // Check if the email and password match in your database
        // If authentication succeeds, redirect to the Home controller
        if ($authenticated) {
            return redirect()->to('home');
        } else {
            // If authentication fails, redirect back to the login page with an error message
            return redirect()->to('login')->with('error', 'Invalid email or password');
        }
    }
    public function main()
    {
        $model = new UserModel();
        // $userCount = $this->$model->getUserCount();

        // echo "Total number of users: " . $userCount;

        return view('welcome_message' );
    }


    // public function get()
    // {

    //     return ('welcome_message');
    // }
}
