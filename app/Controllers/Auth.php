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
        $required_fields = ['mobile_number'];
        foreach ($required_fields as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                return "Error: Missing required field '$field'";
            }
        }
        // die();
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
            $otp =  $this->otp($input['mobile_number']);
            if ($otp['success'] == true) {
                return $this
                    ->getResponse(
                        [
                            'message' => 'Otp send successfully',
                            'otp' => $otp['otp'],
                            'status' => 'success'

                        ]
                    );
            } else {
                return $this
                    ->getResponse(
                        [
                            'message' => 'otp send failed',


                        ]
                    );
            }

            // return $this->getJWTForUser($input['mobile_number']);
        }
    }

    public function otp($data)
    {
        $mobileNumber = $data;
        // Generate OTP
        // Generate OTP
        $otp1 = '1234';

        session_start();
        // Save OTP to the user's session

        $_SESSION['otp'] = $otp1;
        $_SESSION['mobile'] = $mobileNumber;
        $_SESSION['otp_time'] = time();

        // var_dump($_SESSION);

        $url = 'https://www.fast2sms.com/dev/bulkV2';
        $apiKey = 'fXeO8yi0IF29xhjVN5LTB6slYdRrEkSJv3ZtWcMHaoqbPDuAUmLuihz0I8CkVM34y7KJxEeGlFBsSvQt';
        $otp = $otp1;
        $mobileNumber1 = $mobileNumber;
        $route = 'otp';
        $variablesValues = $otp;
        $flash = '0';

        // Construct the URL with parameters appended
        $url .= '?authorization=' . urlencode($apiKey) .
            '&route=' . urlencode($route) .
            '&variables_values=' . urlencode($variablesValues) .
            '&flash=' . urlencode($flash) .
            '&numbers=' . urlencode($mobileNumber1);

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options for a GET request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['success' => false, 'error' => 'cURL Error: ' . $error];
        }

        // Close cURL session
        curl_close($ch);
// print_r($response);
// die();
        // Check response and handle errors if necessary
        if ($response === false) {
            return ['success' => false, 'error' => 'Failed to send GET request'];
        } else {
            return ['success' => true, 'otp' => $otp1];
        }
    }
    public function verifyOTP($userOTP)
    {
        // 
        $input = $this->getRequestInput($this->request);
        $sentMobile = $input['mobile_number'];
      
        // Get the OTP and its creation time from the session
        $sentOTP = '1234';
        // $otpTime = $_SESSION['otp_time'];
        // $mobile = $_SESSION['mobile'];
        // echo $sentOTP;
        // Check if the OTP was created more than 5 minutes ago
        // if (time() - $otpTime > 5 * 60) {
        //     // OTP expired, clear session variables and return false
        //     unset($_SESSION['otp']);
        //     unset($_SESSION['otp_time']);
        //     return false;
        // }

        // Compare the user-provided OTP with the one stored in the session
        if ($userOTP == $sentOTP ) {
            // OTP matches, return true
            // return true;
            // echo "prr";
            return $this->getJWTForUser($input['mobile_number']);
        } else {
            // OTP does not match, return false
            return false;
        }
    }
    public function register()
    {
        $input = $this->getRequestInput($this->request);
        // echo "<pre>"; print_r($input); echo "</pre>";
        // die();
        $required_fields = ['mobile_number', 'role'];
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

                $required_fields = ['name', 'gender', 'mobile_number', 'company_details', 'profile_picture', 'address', 'city', 'country', 'gst_number', 'field_of_company', 'contact_information'];
                foreach ($required_fields as $field) {
                    if (!isset($input[$field]) || empty($input[$field])) {
                        return "Error: Missing required field '$field'";
                    }
                }

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

                $data = $input;
                $data['user_id'] = $foruid['id'];
                $required_fields = ['user_id', 'name', 'resume', 'gender', 'email', 'profile_picture', 'address', 'city', 'country', 'interested_fields', 'other_personal_details'];
                foreach ($required_fields as $field) {
                    if (!isset($data[$field]) || empty($data[$field])) {
                        return "Error: Missing required field '$field'";
                    }
                }
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
            $input['id'] = $id;
            $input = $this->getRequestInput($this->request);
            $required_fields = ['id', 'name', 'resume', 'gender', 'email', 'profile_picture', 'address', 'city', 'country', 'interested_fields', 'other_personal_details'];
            foreach ($required_fields as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    return "Error: Missing required field '$field'";
                }
            }
            $model->update1($id, $input);
            $post = $model->findUserById($id);
            return $this->getResponse(
                [
                    'message' => 'user updated successfully',
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