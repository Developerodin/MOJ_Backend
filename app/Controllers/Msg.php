<?php

namespace App\Controllers;

use App\Models\MsgModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Session\Session;
use ReflectionException;

class Msg extends BaseController
{
    use ResponseTrait;
    protected $session;
   
    public function index()
    {
        //    echo "test";
        //    die();

        $model = new MsgModel();
        $post = $model->findAll();
        return $this->getResponse(
            [
                'message' => 'Msg retrieved successfully',
                'post' => $post,
                'status' => 'success'
            ]
        );
    }

    public function save()
    {
        $input = $this->getRequestInput($this->request);
        $model = new MsgModel();
       
        // $required_fields = ['user_id', 'job_title', 'job_description', 'job_type', 'skill_requirements', 'location', 'department', 'experience_requirements'];
        // foreach ($required_fields as $field) {
        //     if (!isset($input[$field]) || empty($input[$field])) {
        //         return "Error: Missing required field '$field'";
        //     }
        // }

        $post = $model->save($input);

        

        return $this->getResponse(
            [
                'message' => 'Msg  added successfully',
                'job' => $post,
                
                'status' => 'success'

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new MsgModel();
            $post = $model->findById($id);

           

            return $this->getResponse(
                [
                    'message' => 'Msg retrieved successfully',
                    'Job' => $post,
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
    public function user_show($id)
    {
        // user_id pass
        try {
            $model = new MsgModel();
            $data['sender'] = $model->getallsendData($id);
            $data['reciver'] = $model->getallrecData($id);


            
            // $model1 = new UserModel();
            // $hotel = $model1->getHUserData($id);
            return $this->getResponse(
                [
                    'message' => 'msg retrieved successfully',
                    'Job' => $data,
                    // 'hotel' => $hotel ,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find msg for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function res_show($id)
    {
        // user_id pass
        try {
            $model = new MsgModel();
            $post = $model->getallrecData($id);
            // $model1 = new UserModel();
            // $hotel = $model1->getHUserData($id);
            return $this->getResponse(
                [
                    'message' => 'msg retrieved successfully',
                    'Job' => $post,
                    // 'hotel' => $hotel ,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find msg for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

   
    public function destroy($id)
    {
        try {
            $model = new MsgModel();
            $model->deletedata($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Msg deleted successfully',
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

