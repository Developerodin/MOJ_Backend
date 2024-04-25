<?php

namespace App\Controllers;

use App\Models\JobApplyModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;
use CodeIgniter\API\ResponseTrait;

use ReflectionException;

class Job_Apply extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        //    echo "test";
        //    die();

        $model = new JobApplyModel();

        return $this->getResponse(
            [
                'message' => 'Applyed Job retrieved successfully',
                'post' => $model->findAll()
            ]
        );
    }

    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new JobApplyModel();
        $required_fields = ['user_id', 'job_id' ];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }


        $data = [

            'hotelier_id' => $input['user_id'],
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
                'job' => $post

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new JobApplyModel();
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

    public function update($id)
    {
        try {
            $model = new JobApplyModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id, $input);
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'job updaetd successfully',
                    'job' => $post
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
            $model = new JobApplyModel();
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
