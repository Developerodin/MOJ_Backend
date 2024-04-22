<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\JobModel;

use App\Models\BasicModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use CodeIgniter\API\ResponseTrait;
use ReflectionException;

class Auth extends BaseController
{
    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */
    public function check_mobile()
    {
        $input = $this->getRequestInput($this->request);
        // echo "<pre>"; print_r($input); echo "</pre>";
        // die();
        $required_fields =['mobile_number'] ;
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }
        $model = new UserModel();

        $user = $model->findUserByUserNumber1($input['mobile_number']);
        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die();
        if ($user == 0) {
            $response = $this->response->setStatusCode(200)->setBody('user not found');
            return $response;
        } else {

            return $this->getJWTForUser($input['mobile_number']);
        }
    }
    public function register()
    {
        $input = $this->getRequestInput($this->request);
        // echo "<pre>"; print_r($input); echo "</pre>";
        // die();
        $required_fields =['name', 'resume', 'gender', 'email', 'profile_picture', 'address', 'city', 'country', 'interested_fields', 'other_personal_details'] ;
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }
        $model = new UserModel();

        $user = $model->findUserByUserNumber1($input['mobile_number']);

        if ($user == 0) {

            $snew = $model->save($input['mobile_number']);
            // echo "<pre>"; print_r($snew); echo "</pre>";

            $foruid = $model->findUserByUserNumber($input['mobile_number']);
            // echo "<pre>";
            // print_r($foruid);
            // echo "</pre>";

            if ($input['role'] == 'Hoteliers') {
                // echo "<pre>";
                // print_r("h");
                // echo "</pre>";
                // die();
                $data = [

                    'user_id' => $foruid['id'],
                    'name' => $input['name'],
                    'gender' => $input['gender'],
                    'mobile_number' => $input['mobile_number'],
                    'company_details' => $input['company_details'],
                    'profile_picture' => $input['profile_picture'],
                    'address' => $input['address'],
                    'city' => $input['city'],
                    'role' => $input['role'],
                    'country' => $input['country'],
             
                    'gst_number' => $input['gst_number'],
                    'field_of_company' => $input['field_of_company'],
                    'contact_information' => $input['contact_information'],
                ];

                $user1 = $model->save_hprofile($data);
            } else {
//  echo "<pre>";
//                 print_r($input);
//                 echo "</pre>";
//                 die();
$data = $input;
                $data['user_id'] = $foruid['id'];
                // echo "<pre>";
                //             print_r($data);
                //             echo "</pre>";
                //             die();
                $user1 = $model->save_profile($data);
            }

            return $this
                ->getResponse(
                    [
                        'message' => 'User Register successfully',
                        'user' => $user1,


                    ]
                );

            // echo json_encode( $wallet );
            // die();
        } else {
            // echo "test";
            $user1 = null;
            $response =
                $this->response->setStatusCode(400)->setBody('user allrady in list');
            return $response;
        }
    }
    public function basic()
    {
        $model = new BasicModel();
        $basic = $model->findAll();
        return $basic;
    }

    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login($data)
    {
        $rules = [

            'moblie_number' =>
            'required|min_length[10]|max_length[10]|validateUser[user_number, pin]'
        ];

        $errors = [
            'mobile_number' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);
        // echo json_encode($input);
        if ($this->validateRequest($input, $rules, $errors)) {

            return $this->getJWTForUser($input['mobile_number']);
        } else {
            // return $this->getResponse($input);
            $response =
                $this->response->setStatusCode(400)->setBody('Invalid login Mobile Number');
            return $response;
        }
    }

    public function user_update($id)
    {
        try {
            $model = new UserModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id, $input);
            $post = $model->findUserById($id);
            return $this->getResponse(
                [
                    'message' => 'user updaetd successfully',
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

    private function getJWTForUser(
        string $mobile_Number,
        int $responseCode = ResponseInterface::HTTP_OK
    ) {

        try {
            $model = new UserModel();

            $user = $model->findUserByUserNumber($mobile_Number);
            $userd = $model->getUserData($user['id']);
            if ($userd == null) {
                $userd = $model->getUserHData($user['id']);
            }
            $model11 = new JobModel();

            // if($userd['role'] == 'Hoteliers'){
            //     $jobdata= $model11->findAll();
            // }else{
            //     $jobdata= $model11->findAll();
            // }
            // echo "<pre>";
            // print_r($jobdata);
            // echo "</pre>";
            // die();




            // unset('1234');
            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $userd,
                        // 'Job-Data' => $jobdata,

                        'access_token' => getSignedJWTForUser($mobile_Number)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
    private function getJWTForNewUser(
        string $mobile_number,
        int $responseCode = ResponseInterface::HTTP_OK
    ) {

        try {
            $model = new UserModel();
            $user = $model->findUserByUserNumber($mobile_number);
            // echo json_encode($user);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User Created successfully',

                        'access_token' => getSignedJWTForUser($mobile_number)
                    ]
                );
        } catch (Exception $exception) {
            return $this
                ->getResponse(
                    [
                        'error' => $exception->getMessage(),
                    ],
                    $responseCode
                );
        }
    }
}
