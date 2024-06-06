<?php

namespace App\Controllers;

use App\Models\Job_prefModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Session\Session;
use ReflectionException;

class Job_pref extends BaseController
{
    use ResponseTrait;
    protected $session;
   
    public function get()
    {
        //    echo "test";
        //    die();

        $model = new Job_prefModel();

        return $this->getResponse(
            [
                'message' => 'Job pref retrieved successfully',
                'post' => $model->findAll(),
                'status' => 'success'
            ]
        );
    }

    public function save()
    {
        $input = $this->getRequestInput($this->request);
        $model = new Job_prefModel();
        $required_fields = ['department', 'sub_department'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }


        $data = [

            'sub_department' => $input['sub_department'],
            'department' => $input['department'],
            

        ];
        // echo "<pre>";
        //             print_r($data);
        //             echo "</pre>";
        //             die();
        $post = $model->save($data);


        return $this->getResponse(
            [
                'message' => 'Job  added successfully',
                'post' => $data,
                'status' => 'success'

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new Job_prefModel();
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'Job pref retrieved successfully',
                    'post' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job pref for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function sub_show()
    {
        $input = $this->getRequestInput($this->request);
        // user_id pass
        try {
            $model = new Job_prefModel();
            // echo "test";
            $post = $model->getsubData($input['department']);
            return $this->getResponse(
                [
                    'message' => 'Job pref retrieved successfully',
                    'post' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job pref for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function update($id)
    {
        try {
            $model = new Job_prefModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id, $input);
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'job pref updaetd successfully',
                    'post' => $post,
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
    public function destroy($id)
    {
        try {
            $model = new Job_prefModel();
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



// for user



    public function user_get()
    {
        //    echo "test";
        //    die();

        $model = new Job_prefModel();

        return $this->getResponse(
            [
                'message' => 'user Job pref retrieved successfully',
                'post' => $model->getuser_job(),
                'status' => 'success'
            ]
        );
    }

    public function user_save()
    {
        $input = $this->getRequestInput($this->request);

        $model = new Job_prefModel();
        $required_fields = ['user_id','job_type', 'department','pref_state','pref_city','salery'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }

   
        $user = $model->show_userid($input['user_id']);
        if($user){
// print_r($user);


            // $id = $user[0]['user_id'];
            // print_r($id);
            $post = $model->user_update11($input['user_id'], $input);
            $message = 'Job pref update successfully';
        }else{
            $post = $model->user_save($input);
            $message = 'Job pref added successfully';
        }
       
        return $this->getResponse(
            [
                'message' => $message,
                
                'status' => 'success'

            ]
        );
    }

    public function user_show($id)
    {
        // user_id pass
        try {
            $model = new Job_prefModel();
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'Job pref retrieved successfully',
                    'post' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job pref for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function show_userid($id)
    {
       
        // user_id pass
        try {
            $model = new Job_prefModel();
            // echo "test";
            $post = $model->show_userid($id);
            return $this->getResponse(
                [
                    'message' => 'Job pref retrieved successfully',
                    'post' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job pref for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function user_update($id)
    {
        try {
            $model = new Job_prefModel();
            $input = $this->getRequestInput($this->request);
            $model->user_update1($id, $input);
            $post = $model->show_uid($id);
            return $this->getResponse(
                [
                    'message' => 'job pref updaetd successfully',
                    'post' => $post,
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
    public function user_destroy($id)
    {
        try {
            $model = new Job_prefModel();
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

