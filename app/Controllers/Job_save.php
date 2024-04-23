<?php

namespace App\Controllers;
use App\Models\JobSaveModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;
use CodeIgniter\API\ResponseTrait;

use ReflectionException;
class Job_save extends BaseController
{
    use ResponseTrait;
    public function index()
    {
    //    echo "test";
    //    die();

        $model = new JobSaveModel();
$post = $model->findAll();
if(!$post){
    return $this->getResponse(
        [
            'message' => 'Job not found successfully',
           
        ]
    );
}else{
    return $this->getResponse(
        [
            'message' => 'Job retrieved successfully',
            'post' => $post
        ]
    );
}
        
    }
    
    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new JobSaveModel();
       
        $data = [

            'user_id' => $input['user_id'],
            'job_id' => $input['job_id'],
            
            
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
            $model = new JobSaveModel();
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
            $model = new JobSaveModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id ,$input);
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
            $model = new JobSaveModel();
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
