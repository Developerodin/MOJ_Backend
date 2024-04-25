<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use ReflectionException;

class Users extends BaseController
{

    public function work_ex()
    {
        $input = $this->getRequestInput($this->request);
        // echo "<pre>"; print_r($input); echo "</pre>";
        // die();
        $required_fields = ['user_id', 'organisation', 'designation', 'profile', 'location', 'start_date', 'end_date'];
            foreach ($required_fields as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    return "Error: Missing required field '$field'";
                }
            }
        $model = new UserModel();
        $data = [

            'user_id' => $input['user_id'],
            'organisation' => $input['organisation'],
            'designation' => $input['designation'],
            'profile' => $input['profile'],
            'location' => $input['location'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date']
        ];
        // echo "<pre>";
        //             print_r($data);
        //             echo "</pre>";
        //             die();
        $user1 = $model->save_workex($data);

        if ($user1 == true) {
            return $this
                ->getResponse(
                    [
                        'message' => 'User Work experience add successfully',
                        'user' => $data,


                    ]
                );

        } else {
           
            $response =
                $this->response->setStatusCode(400)->setBody('User Work experience not updated');
            return $response;
        }
    }
    public function work_ex_update($id)
    {
       
        $input = $this->getRequestInput($this->request);
        // echo "<pre>"; print_r($input); echo "</pre>";
        // die();
        $required_fields = ['organisation', 'designation', 'profile', 'location', 'start_date', 'end_date'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }
        $model = new UserModel();
        $data = [

            'user_id' => $id,
            'organisation' => $input['organisation'],
            'designation' => $input['designation'],
            'profile' => $input['profile'],
            'location' => $input['location'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date']
        ];
        // echo "<pre>";
        //             print_r($data);
        //             echo "</pre>";
        //             die();
        $user1 = $model->save_workex($data);

        if ($user1 == true) {
            return $this
                ->getResponse(
                    [
                        'message' => 'User Work experience add successfully',
                        'user' => $data,


                    ]
                );

        } else {
           
            $response =
                $this->response->setStatusCode(400)->setBody('User Work experience not updated');
            return $response;
        }
    }
    // ---- banner ===//


    // g deleted the
    public function destroy($id)
    {


        try {
            $model = new UserModel();
            $post = $model->findPostById($id);
            $model->deletedata($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Post deleted successfully',
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

     //display data w/o id
     public function get()
     {
         $model = new UserModel();
         $data = $model->get_data();
 
         if ($data == null) {
             $response =
                 $this->response->setStatusCode(400)->setBody(' No Data found');
             return $response;
         } else {
             return $this
                 ->getResponse(
                     [
                         'message' => 'Data found successfully ',
                         'data' => $data
 
                     ]
                 );
         }
     }
 
     //display data by using id
     public function work_show($id)
     {
         $model = new UserModel();
         $data = $model->getby_id_data($id);
         if ($data == null) {
             $response =
                 $this->response->setStatusCode(400)->setBody(' Id Not found');
             return $response;
         } else {
             return $this
                 ->getResponse(
                     [
                         'message' => 'Id found successfully ',
                         'data' => $data
 
                     ]
                 );
         }
     }
 
     public function delete_w_ex($id)
     {
         try {
             $model = new UserModel();
             // $post = $model->findPostById($id);
             $model->delete_w_ex($id);
             return $this
                 ->getResponse(
                     [
                         'message' => 'work exp. deleted successfully',
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