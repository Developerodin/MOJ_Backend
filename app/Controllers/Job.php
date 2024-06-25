<?php

namespace App\Controllers;

use App\Models\JobModel;
use App\Models\Job_prefModel;
use App\Models\ProfileModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Session\Session;
use ReflectionException;

class Job extends BaseController
{
    use ResponseTrait;
    protected $session;

    public function index()
    {
        //    echo "test";
        //    die();

        $model = new JobModel();
        $post = $model->getallJobData();
        return $this->getResponse(
            [
                'message' => 'Job retrieved successfully',
                'post' => $post,
                'status' => 'success'
            ]
        );
    }
    public function job_prf($id)
{
    try {
        // Fetch all job data
        $model = new JobModel();
        $post = $model->getallJobData();

        // Fetch user job preferences based on user id
        $model3 = new Job_prefModel();
        $job_pre = $model3->show_userid($id);

        if (!$post || !$job_pre) {
            throw new Exception("Error fetching job data or user preferences.");
        }

        // Function to match jobs with user preferences
        function matchJobs($jobs, $preferences) {
            $matches = [];

            foreach ($preferences as $pref) {
                foreach ($jobs as $job) {
                    // Convert department and sub_dep strings to arrays for comparison
                    $prefDepartments = explode(',', $pref->department);
                    $prefSubDepartments = explode(',', $pref->sub_dep);
                    $jobDepartments = explode(',', $job->department);
                    $jobSubDepartments = explode(',', $job->sub_department);

                    // Check if any department or sub-department matches
                    $departmentMatch = !empty(array_intersect($prefDepartments, $jobDepartments));
                    $subDepartmentMatch = !empty(array_intersect($prefSubDepartments, $jobSubDepartments));

                    // Check if state and city match
                    $stateMatch = $job->state == $pref->pref_state;
                    $cityMatch = $job->city == $pref->pref_city;

                    // Check if salary range matches
                    $jobSalaryRange = explode('-', $job->off_salery);
                    $jobMinSalary = intval(trim(str_replace(',', '', $jobSalaryRange[0])));
                    $jobMaxSalary = isset($jobSalaryRange[1]) ? intval(trim(str_replace(',', '', $jobSalaryRange[1]))) : $jobMinSalary;
                    $prefSalaryRange = explode('-', $pref->salery);
                    $prefMinSalary = intval(trim(str_replace(',', '', $prefSalaryRange[0])));
                    $prefMaxSalary = isset($prefSalaryRange[1]) ? intval(trim(str_replace(',', '', $prefSalaryRange[1]))) : $prefMinSalary;
                    $salaryMatch = $jobMaxSalary >= $prefMinSalary && $jobMinSalary <= $prefMaxSalary;

                    // Include job if state and city match and any of the other criteria match
                    if ($stateMatch && $cityMatch && ($departmentMatch || $subDepartmentMatch || $salaryMatch)) {
                        $matches[] = $job;
                    }
                }
            }

            return $matches;
        }

        // Get matching jobs
        $matchingJobs = matchJobs($post, $job_pre);

        return $this->getResponse(
            [
                'message' => 'Job retrieved successfully according to preferences',
                'post' => $matchingJobs,
                'status' => 'success'
            ]
        );
    } catch (Exception $e) {
        return $this->getResponse(
            [
                'message' => 'An error occurred: ' . $e->getMessage(),
                'status' => 'error'
            ],
            500 // HTTP status code for Internal Server Error
        );
    }
}



    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new JobModel();

        // $required_fields = ['user_id', 'job_title', 'job_description', 'job_type', 'skill_requirements', 'location', 'department', 'experience_requirements'];
        // foreach ($required_fields as $field) {
        //     if (!isset($input[$field]) || empty($input[$field])) {
        //         return "Error: Missing required field '$field'";
        //     }
        // }

        $post = $model->save($input);



        return $this->getResponse(
            [
                'message' => 'Job  added successfully',
                'job' => $post,

                'status' => 'success'

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new JobModel();
            $post = $model->getJobDataid($id);



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
            $model = new JobModel();
            $post = $model->getJobData($id);
            // $model1 = new UserModel();
            // $hotel = $model1->getHUserData($id);
            return $this->getResponse(
                [
                    'message' => 'Job retrieved successfully',
                    'Job' => $post,
                    // 'hotel' => $hotel ,
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

    public function update($id)
    {
        try {
            $model = new JobModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id, $input);
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'job  updaetd successfully',
                    'job' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'message' => $exception->getMessage(),
                    'status' => 'error',
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function st_update($id)
    {
        try {
            $model = new JobModel();
            $input = $this->getRequestInput($this->request);
            $model->update_st($id, $input);
            $post = $model->findJobById($id);
            return $this->getResponse(
                [
                    'message' => 'Job Status updaetd successfully',
                    'job' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $exception) {
            return $this->getResponse(
                [
                    'message' => $exception->getMessage(),
                    'status' => 'error',
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function destroy($id)
    {
        try {
            $model = new JobModel();
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
