<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\JobModel;

use App\Models\BasicModel;
use App\Models\initialModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use ReflectionException;

class initial extends BaseController
{
 //to insert data
 public function save_data()
 {
     
         $input = $this->getRequestInput($this->request);
         $model = new initialModel();
        
         $data = [

             'role' => $input['role'],
             'PhoneNo' => $input['PhoneNo'],
             'ReferralCode' => $input['ReferralCode'],
             'profile_picture' => $input['profile_picture'],
             'FirstName' => $input['FirstName'],
             'LastName' => $input['LastName'],
             'Gender' => $input['Gender'],
             'Email' => $input['State'],
             'State' => $input['job_id'],
             'City' => $input['City'],
             'Education' => $input['Education'],
             'JobPrefrences' => $input['JobPrefrences'],
             'WorkExperience' => $input['WorkExperience'],
             'Resume' => $input['Resume'],
             'created_at' => $input['created_at'],
             'updated_at' => $input['updated_at'],
                         
         ];
         $post = $model->saved($data);
         if ($post==null){
             $response =
             $this->response->setStatusCode(400)->setBody('Retry.. Not Registered.');
         return $response;

        
         }
              else{
                 return $this
                 ->getResponse(
                     [
                         'message' => 'Registered successfully',
                      
                     ]
                 );
     }
 }

}