<?php

namespace App\Controllers;
use App\Models\BasicModel;
use App\Models\UserAModel;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

class Basic extends BaseController
{
   
    public function index()
    {
        $model = new BasicModel();

        return $this->getResponse(
            [
                'message' => 'Details retrieved successfully',
                'post' => $model->findAll(),
                'status' => 'success',
            ]
        );
    }
    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new BasicModel();
        $required_fields = ['whatsapp', 'mobile', 'email', 'hiw'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }


        $data = [

            'whatsapp' => $input['whatsapp'],
            'mobile' => $input['mobile'],
            'email' => $input['email'],
            'hiw' => $input['hiw'],
           

        ];
        // echo "<pre>";
        //             print_r($data);
        //             echo "</pre>";
        //             die();
        $post = $model->save($data);


        return $this->getResponse(
            [
                'message' => 'Basic details   added successfully',
                'job' => $post,
                'status' => 'success'

            ]
        );
    }
        public function update()
    {
        try {
            $model = new BasicModel();
            $input = $this->getRequestInput($this->request);
            $model->update_num($input);
            $post = $model->findAll();
            return $this->getResponse(
                [
                    'message' => 'detail updaetd successfully',
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

    public function delete($id)
    {
        try {
            $model = new BasicModel();
            // $post = $model->findPostById($id);
            $model-> delete_us($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Basic details deleted successfully',
                        'status' => 'success',
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
