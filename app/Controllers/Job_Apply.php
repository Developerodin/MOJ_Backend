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

    public function save_data()
    {

        $input = $this->getRequestInput($this->request);
        $model = new JobApplyModel();

        $data = [

            'job_id' => $input['job_id'],
            'candidate_id' => $input['candidate_id'],


        ];
        $post = $model->saved($data);
        if ($post == null) {
            $response =
                $this->response->setStatusCode(400)->setBody('Job Application not submitted');
            return $response;
        } else {
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

        $model = new JobApplyModel();
        $input = $this->getRequestInput($this->request);
        $post = $model->findJobAppById1($id);

        if ($post == 0) {
            $response =
                $this->response->setStatusCode(400)->setBody('Job Application not found');
            return $response;
        } else {

            $post = $model->update1($id);
            $post = $model->findJobAppById($id);
            return $this->getResponse(
                [
                    'message' => 'user updated successfully',
                    'client' => $post
                ]
            );
        }
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
