<?php

namespace App\Controllers;
use App\Models\JobModel;
use App\Models\JobAppModel;
use App\Models\UserModel;


use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;

use ReflectionException;
class JobApplication extends BaseController
{
    public function index()
    {
        $model = new JobAppModel();

        return $this->getResponse(
            [
                'message' => 'Job retrieved successfully',
                'post' => $model->findAll()
            ]
        );
    }
    //to insert data


    
    public function save_data()
    {
        
            $input = $this->getRequestInput($this->request);
            $model = new JobAppModel();
           
            $data = [

                'job_id' => $input['job_id'],
                'candidate_id' => $input['candidate_id'],
               
                
            ];
            $post = $model->saved($data);
            if ($post==null){
                $response =
                $this->response->setStatusCode(400)->setBody('Job Application not submitted');
            return $response;

           
            }
                 else{
                    return $this
                    ->getResponse(
                        [
                            'message' => 'Job Application submitted successfully',
                         
                        ]
                    );
        }
    }


    


    public function user_update($id)
    {
        
            $model = new JobAppModel();
            $input = $this->getRequestInput($this->request);
            $post = $model->findJobAppById1($id);
         
            if($post == 0){
                $response =
                $this->response->setStatusCode(400)->setBody('Job Application not found');
            return $response;
            }
            else{

                $post= $model->update1($id);
            $post = $model->findJobAppById($id);
            return $this->getResponse(
                [
                    'message' => 'user updated successfully',
                    'client' => $post
                ]
            );
  
    }
}
}