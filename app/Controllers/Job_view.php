<?php

namespace App\Controllers;

use App\Models\JobViewModel;


use App\Models\BasicModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use CodeIgniter\API\ResponseTrait;
use ReflectionException;

class Job_view extends BaseController
{
  
    public function index()
    {
        
       
        $model = new JobViewModel();
        // echo "test";
        // die();
        $post = $model->findAll();
        if (!$post) {
            return $this->getResponse(
                [
                    'message' => 'Job not found successfully',

     
                ]
            );
        } else {
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
                    'post' => $post,
                'status' => 'success'
                ]
            );
        }
    }

    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new JobViewModel();
        // echo "<pre>";
        // print_r($input);
        // echo "</pre>";
        // die();
        $required_fields = ['user_id', 'job_id'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }
        $data = [

            'user_id' => $input['user_id'],
            'job_id' => $input['job_id'],


        ];

        $post = $model->save($data);

        // echo "test";
        // die();
        return $this->getResponse(
            [
                'message' => 'Job saved successfully',
                'job' => $post,
                'status' => 'success'

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new JobViewModel();
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'Save Job retrieved successfully',
                    'Job' => $post,
                'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'save Could not find Job for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function user_show($id)
    {
        // user_id pass
        try {
            $model = new JobViewModel();
            $post = $model->findByuserId($id);

            return $this->getResponse(
                [
                    'message' => 'Save Job retrieved successfully',
                    'Job' => $post,
                'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find save Job for specified user ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

   
    public function distroy($id)
    {
        try {
            $model = new JobViewModel();
            $model->deletedata($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Job deleted successfully',
                'status' => 'success'
                    ]
                );
        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
}
