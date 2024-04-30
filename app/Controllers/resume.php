<?php

namespace App\Controllers;

use App\Models\ResumeModel;


use App\Models\BasicModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use CodeIgniter\API\ResponseTrait;
use ReflectionException;

class resume extends BaseController
{
  
    public function index()
    {
        
       
        $model = new ResumeModel();
        // echo "test";
        // die();
        $post = $model->findAll();
        if (!$post) {
            return $this->getResponse(
                [
                    'message' => 'Resume  not found ',

                ]
            );
        } else {
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
                    'post' => $post
                ]
            );
        }
    }

    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new ResumeModel();
        // echo "<pre>";
        // print_r($input);
        // echo "</pre>";
        // die();
        $required_fields = ['user_id', 'resume'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }
        $data = [

            'user_id' => $input['user_id'],
            'resume' => $input['resume'],


        ];

        $post = $model->save($data);

        // echo "test";
        // die();
        return $this->getResponse(
            [
                'message' => 'Job saved successfully',
                'job' => $post

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new ResumeModel();
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
                    'Job' => $post
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    // public function update($id)
    // {
    //     try {
    //         $model = new ResumeModel();
    //         $input = $this->getRequestInput($this->request);
    //         $model->update1($id, $input);
    //         $post = $model->findJobById($id);
    //         return $this->getResponse(
    //             [
    //                 'message' => 'job updaetd successfully',
    //                 'job' => $post
    //             ]
    //         );
    //     } catch (Exception $exception) {
    //         return $this->getResponse(
    //             [
    //                 'message' => $exception->getMessage()
    //             ],
    //             ResponseInterface::HTTP_NOT_FOUND
    //         );
    //     }
    // }
    public function destroy($id)
    {
        try {
            $model = new ResumeModel();
            $model->deletedata($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Job deleted successfully',
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
