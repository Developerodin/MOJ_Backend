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


        // Get the uploaded file
        $file = $this->request->getFile('resume');

        // Check if the file is uploaded successfully
        if ($file->isValid() && !$file->hasMoved()) {
            // Move the file to the uploads folder
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $newName);

            // Save file information to the database

            $filename = $file->getName();
            $filepath = '/uploads/' . $newName; // Use the new name for the file path
            $filedata = file_get_contents($file->getTempName());

            // Save file information to the database


            $model = new ResumeModel();
            $existingResume = $model->findByUId($input['user_id']);
            // echo "test";
            // die();
            if ($existingResume == null) {
                // If there's no existing resume, create a new folder for the user
                $userFolder = WRITEPATH . 'uploads/' . $input['user_id'] . '-resume';
                if (!file_exists($userFolder)) {
                    mkdir($userFolder, 0777, true); // Create user's folder if it doesn't exist
                }
            } else {

                
                $existingFilePath = WRITEPATH .$existingResume['Resume'];
            //     If there's an existing resume, delete the previous file and folder
            //     echo "$existingFilePath";
            // die();
                if (file_exists($existingFilePath)) {
                    unlink($existingFilePath); // Delete the existing file
                }else{
                    echo "File not exists";
                }
               
            }

         
            // Move the file to the user's folder
            $userResumeFolder = WRITEPATH . 'uploads/' . $input['user_id'] . '-resume';
            $newResumePath = $userResumeFolder . '/' . $newName;
            rename(WRITEPATH . 'uploads/' . $newName, $newResumePath);
            $res_p = '/uploads/' . $input['user_id'] . '-resume/' . $newName;

            $data = [
                'user_id' => $input['user_id'],
                'resume' => $res_p // Save the file path
                // You can add more information about the file as needed
            ];
            // Update or save the resume information in the database
            if ($existingResume == null) {
                $post = $model->save($data);
            } else {
                $post = $model->update1($data);
            }

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
            $post = $model->findByUId($id);
            $data['user_id'] = $post['user_id'];
            $resume1 = $post['Resume'];
            $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration


            // Remove "public" segment from the base URL
            $baseUrl = str_replace('/public/', '/', $baseUrl);

            // Now, $baseUrl will be 'https://dashboard.masterofjobs.in/'

            $data['resume'] = $baseUrl . 'writable' . $resume1;
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
