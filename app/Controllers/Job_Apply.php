<?php

namespace App\Controllers;

use App\Models\JobApplyModel;
use App\Models\UserModel;
use App\Models\ProfileModel;
use App\Models\Job_prefModel;
use App\Models\ResumeModel;
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

        $data = [

            'job_id' => $input['job_id'],
            'user_id' => $input['user_id'],
            'resume_id' => $input['resume_id'],


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
                        'status' => 'success'

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

            $post = $model->update($id);
            $post = $model->findJobAppById($id);
            return $this->getResponse(
                [
                    'message' => 'user updated successfully',
                    'client' => $post,
                    'status' => 'success'
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
    public function count_job($id)
    {
        // user_id pass
        try {
            $model = new JobApplyModel();
            $post = $model->getjobCount($id);
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
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
            $model = new JobApplyModel();
            $post = $model->getJobData($id);
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
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
    public function show_user($id)
    {
        try {
            $model = new JobApplyModel();
            $posts = $model->findJobByjobId($id); // Find all job applications by job ID

            if ($posts) {
                $data = []; // Initialize an array to hold all user data

                foreach ($posts as $post) {

                    $application_id = $post['id'];
                    $application_status = $post['status'];
                    $user_id = $post['user_id'];
                    $user = new UserModel();
                    $udata = $user->getUserData($user_id);

                    $profile = new ProfileModel();
                    $post1 = $profile->findByUId($user_id);
                    $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration
                    $baseUrl = str_replace('/public/', '/', $baseUrl);

                    if ($post1 !== null) {
                        $resume1 = $post1['image_path'];
                        $existingFilePath = WRITEPATH . $resume1;

                        if (file_exists($existingFilePath)) {
                            $user_img = $baseUrl . 'writable' . $resume1;
                        } else {
                            $user_img = $baseUrl . 'images/user_img.png';
                        }
                    } else {
                        $user_img = $baseUrl . 'public/images/user_img.png';
                    }

                    // work exp
                    $work = $user->getby_id_data($user_id);

                    // job pref
                    $model3 = new Job_prefModel();
                    $job_pre = $model3->show_userid($user_id);

                    // reusme 4
                    $model4 = new ResumeModel();
                    $post4 = $model4->findByUId($user_id);
if($post4){

                    $resume3 = $post4['Resume'];


                    // Now, $baseUrl will be 'https://dashboard.masterofjobs.in/'

                    $user_resume = $baseUrl . 'writable' . $resume3;}else{
                    $user_resume = null;
                    }
                    // Construct user data array
                    $data[] = [
                        'application_id' => $application_id,
                        'application_status' => $application_status,
                        'user_id' => $user_id,
                        'user' => $udata,
                        'user_img' => $user_img,
                        'work' => $work,
                        'job_pref' => $job_pre,
                        'resume' => $user_resume
                    ];
                }

                return $this->getResponse(
                    [
                        'message' => 'Job retrieved successfully',
                        'Job' => $data,
                        'status' => 'success'
                    ]
                );
            } else {
                return $this->getResponse(
                    [
                        'message' => 'No jobs found for specified ID'
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function all_data_user($id)
    {
        try {
            $user = new UserModel();
            $posts = $user->findUserById($id); // Find all job applications by job ID

            if ($posts) {
                $data = []; // Initialize an array to hold all user data


                $user_id = $id;
                $user = new UserModel();
                $udata = $user->getUserData($user_id);

                $profile = new ProfileModel();
                $post1 = $profile->findByUId($user_id);
                $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration
                $baseUrl = str_replace('/public/', '/', $baseUrl);

                if ($post1 !== null) {
                    $resume1 = $post1['image_path'];
                    $existingFilePath = WRITEPATH . $resume1;

                    if (file_exists($existingFilePath)) {
                        $user_img = $baseUrl . 'writable' . $resume1;
                    } else {
                        $user_img = $baseUrl . 'images/user_img.png';
                    }
                } else {
                    $user_img = $baseUrl . 'public/images/user_img.png';
                }

                // work exp
                $work = $user->getby_id_data($user_id);

                // job pref
                $model3 = new Job_prefModel();
                $job_pre = $model3->show_userid($user_id);

                // reusme 4
                $model4 = new ResumeModel();
                $post4 = $model4->findByUId($user_id);
            if($post4){
                $resume3 = $post4['Resume'];
                $user_resume = $baseUrl . 'writable' . $resume3;
            }else{
                $user_resume = null;
            }
                // edu 

                $edu = $user->getUserEd_id($id);


                // Construct user data array
                $data[] = [

                    'user_id' => $user_id,
                    'user' => $udata,
                    'user_img' => $user_img,
                    'work' => $work,
                    'job_pref' => $job_pre,
                    'user_edu' => $edu,
                    'resume' => $user_resume
                ];


                return $this->getResponse(
                    [
                        'message' => 'user retrieved successfully',
                        'Job' => $data,
                        'status' => 'success'
                    ]
                );
            } else {
                return $this->getResponse(
                    [
                        'message' => 'No jobs found for specified ID'
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function all_user_data()
    {
        try {
            $user = new UserModel();
            $posts = $user->findAll(); // Find all users
    
            if ($posts) {
                $data = []; // Initialize an array to hold all user data
                $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration
                $baseUrl = str_replace('/public/', '/', $baseUrl);
    
                foreach ($posts as $post) {
                    $user_id = $post['id'];
    
                    // Get user data
                    $udata = $user->getUserData($user_id);
    
                    // Get profile image
                    $profile = new ProfileModel();
                    $post1 = $profile->findByUId($user_id);
                    if ($post1 !== null) {
                        $resume1 = $post1['image_path'];
                        $existingFilePath = WRITEPATH . $resume1;
    
                        if (file_exists($existingFilePath)) {
                            $user_img = $baseUrl . 'writable' . $resume1;
                        } else {
                            $user_img = $baseUrl . 'images/user_img.png';
                        }
                    } else {
                        $user_img = $baseUrl . 'public/images/user_img.png';
                    }
    
                    // Get work experience
                    $work = $user->getby_id_data($user_id);
    
                    // Get job preferences
                    $model3 = new Job_prefModel();
                    $job_pre = $model3->show_userid($user_id);
    
                    // Get resume
                    $model4 = new ResumeModel();
                    $post4 = $model4->findByUId($user_id);
                    if ($post4) {
                        $resume3 = $post4['Resume'];
                        $user_resume = $baseUrl . 'writable' . $resume3;
                    } else {
                        $user_resume = null;
                    }
    
                    // Get education details
                    $edu = $user->getUserEd_id($user_id);
    
                    // Construct user data array
                    $data[] = [
                        'user_id' => $user_id,
                        'user' => $udata,
                        'user_img' => $user_img,
                        'work' => $work,
                        'job_pref' => $job_pre,
                        'user_edu' => $edu,
                        'resume' => $user_resume
                    ];
                }
    
                return $this->getResponse(
                    [
                        'message' => 'Users retrieved successfully',
                        'Job' => $data,
                        'status' => 'success'
                    ]
                );
            } else {
                return $this->getResponse(
                    [
                        'message' => 'No users found'
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not retrieve users'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function Auser_get()
    {
        try {
      
            $user = new UserModel();
       
            $posts = $user->get_auser(); // Find all users
    //         echo "<pre>";
    // print_r($posts);
    // echo "</pre>";
    // die();
            if ($posts) {
                $data = []; // Initialize an array to hold all user data
                $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration
                $baseUrl = str_replace('/public/', '/', $baseUrl);
    
                foreach ($posts as $post) {
    //                          echo "<pre>";
    // print_r($post);
    // echo "</pre>";
    // die();
                    $user_id = $post->id;
    // echo "test";
    // die();
                    // Get user data
                    $udata = $user->getAUserData($user_id);

                    // Get profile image
                    $profile = new ProfileModel();
                    $post1 = $profile->findByUId($user_id);
                    if ($post1 !== null) {
                        $resume1 = $post1['image_path'];
                        $existingFilePath = WRITEPATH . $resume1;
    
                        if (file_exists($existingFilePath)) {
                            $user_img = $baseUrl . 'writable' . $resume1;
                        } else {
                            $user_img = $baseUrl . 'images/user_img.png';
                        }
                    } else {
                        $user_img = $baseUrl . 'public/images/user_img.png';
                    }
    
    
                    // Construct user data array
                    $data[] = [
                        'user_id' => $user_id,
                        'user' => $udata,
                        'user_img' => $user_img
                    ];
                }
    
                return $this->getResponse(
                    [
                        'message' => 'Agents retrieved successfully',
                        'Job' => $data,
                        'status' => 'success'
                    ]
                );
            } else {
                return $this->getResponse(
                    [
                        'message' => 'No users found'
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not retrieve users'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    
    public function all_data_Huser($id)
    {


        try {
            
            $user = new UserModel();
            $posts = $user->findUserById($id); // Find all job applications by job ID

            if ($posts) {
                $data = []; // Initialize an array to hold all user data


                $user_id = $id;
                $user = new UserModel();
                $Hdata = $user->getHUserData($user_id);

                $profile = new ProfileModel();
                $post1 = $profile->findByUId($user_id);
                $baseUrl = base_url(); // Assuming you have configured the base URL in your CodeIgniter configuration
                $baseUrl = str_replace('/public/', '/', $baseUrl);

                if ($post1 !== null) {
                    $resume1 = $post1['image_path'];
                    $existingFilePath = WRITEPATH . $resume1;

                    if (file_exists($existingFilePath)) {
                        $user_img = $baseUrl . 'writable' . $resume1;
                    } else {
                        $user_img = $baseUrl . 'images/user_img.png';
                    }
                } else {
                    $user_img = $baseUrl . 'public/images/user_img.png';
                }

                

                // Construct user data array
                $data[] = [

                    'user_id' => $user_id,
                    'hoteler_data' => $Hdata,
                    'user_img' => $user_img,

                ];


                return $this->getResponse(
                    [
                        'message' => 'hoteleor retrieved successfully',
                        'Job' => $data,
                        'status' => 'success'
                    ]
                );
            } else {
                return $this->getResponse(
                    [
                        'message' => 'No jobs found for specified ID'
                    ],
                    ResponseInterface::HTTP_NOT_FOUND
                );
            }
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Job for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    public function st_update($id)
    {
        try {
            $model = new JobApplyModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id, $input);
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'job updaetd successfully',
                    'job' => $post,
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
            $model = new JobApplyModel();
            $model->deletedata($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Job deleted successfully',
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
}
