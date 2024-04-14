<?php

namespace App\Controllers;
use App\Models\JobModel;


use App\Models\UserModel;


use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;

use ReflectionException;
class Job extends BaseController
{
    public function index()
    {
        $model = new JobModel();

        return $this->getResponse(
            [
                'message' => 'Job retrieved successfully',
                'post' => $model->findAll()
            ]
        );
    }
    
    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new JobModel();
       
        $data = [

            'hotelier_id' => $input['hotelier_id'],
            'job_title' => $input['job_title'],
            'job_description' => $input['job_description'],
            'job_type' => $input['job_type'],
            'skill_requirements' => $input['skill_requirements'],
            'location' => $input['location'],
            'department' => $input['department'],
            'experience_requirements' => $input['experience_requirements'],
            
        ];
// echo "<pre>";
//             print_r($data);
//             echo "</pre>";
//             die();
        $post = $model->save($data);

        
        return $this->getResponse(
            [
                'message' => 'Job  added successfully',
                'game' => $post
                
            ]
        );
    }
    
    public function show($id)
    {
       // user_id pass
        try {
            $model = new JobModel();
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
                    'client' => $post
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
   
   
   
    
    public function update_num($id)
    {
        try {
            $model = new JobModel();
            $input = $this->getRequestInput($this->request);
            $model->update_num($id ,$input);
            $post = $model->findBById($id);
            return $this->getResponse(
                [
                    'message' => 'Bid updaetd successfully',
                    'client' => $post
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
    
    public function destroy($id)
    {
        try {
            $model = new JobModel();
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
