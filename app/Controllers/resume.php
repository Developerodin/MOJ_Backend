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
                    'post' => $post,
                    'status' => 'success'
                ]
            );
        }
    }

    public function store()
    {
        $input = $this->getRequestInput($this->request);

        // Validate input
        $required_fields = ['user_id', 'resume'];
        // foreach ($required_fields as $field) {
        //     if (!isset($input[$field]) || empty($input[$field])) {
        //         return "Error: Missing required field '$field'";
        //     }
        // }

        // Get the uploaded file
        $file = $this->request->getFile('resume');

        // Check if the file is uploaded successfully
        if ($file->isValid() && !$file->hasMoved()) {
            // Move the file to the uploads folder
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);

            // Save file information to the database
            $model = new ResumeModel();
            $filename = $file->getName();
            $filepath = '/uploads/' . $newName; // Use the new name for the file path
            $filedata = file_get_contents($file->getTempName());

            // Save file information to the database
            $data = [
                'user_id' => $input['user_id'],
                'resume' => $filepath // Save the file path
                // You can add more information about the file as needed
            ];
            $post = $model->save($data);

            return $this->getResponse([
                'message' => 'Resume saved successfully',
                'resume' => $post,
                'status' => 'success'
            ]);
        } else {
            // Handle file upload error
            return "Error uploading file";
        }
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new ResumeModel();
            $post = $model->findJobById($id);

            return $this->getResponse(
                [
                    'message' => 'resume retrieved successfully',
                    'resume' => $post,
                    'status' => 'success'
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
    public function show_userid($id)
    {
        // user_id pass
        try {
            $model = new ResumeModel();
            $post = $model->findJobByUId($id);
            $data['user_id'] = $post['user_id'];
            $resume1 = $post['Resume'];
            $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration
           
          
            // Remove "public" segment from the base URL
            $baseUrl = str_replace('/public/', '/', $baseUrl);
            
            // Now, $baseUrl will be 'https://dashboard.masterofjobs.in/'
            
            $data['resume'] = $baseUrl .'writable'. $resume1;
// echo $data['resume'];
// die();


            return $this->getResponse(
                [
                    'message' => 'resume retrieved successfully',
                    'resume' => $data,
                    'status' => 'success'
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
                        'message' => 'resume deleted successfully',
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
