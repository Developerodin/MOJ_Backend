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
use CodeIgniter\Session\Session;

class Auth extends BaseController
{
    /**
     * Register a new user
     * @return Response
     * @throws ReflectionException
     */

    protected $session;
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }
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
    }

    public function otp($data)
    {
        $mobileNumber = $data;

        $otp1 = rand(100000, 999999);
        // $otp1 = '123456';
        // $this->session->set('otp-' . $mobileNumber, $otp1);


        $url = 'https://www.fast2sms.com/dev/bulkV2';
        $apiKey = 'fXeO8yi0IF29xhjVN5LTB6slYdRrEkSJv3ZtWcMHaoqbPDuAUmLuihz0I8CkVM34y7KJxEeGlFBsSvQt';
        $otp = $otp1;
        $mobileNumber1 = $mobileNumber;
        $route = 'dlt';
        $sender_id = 'JOBMOJ';
        $message = '171550';

        $variablesValues = $otp;
        $flash = '0';

        // Construct the URL with parameters appended
        $url .= '?authorization=' . urlencode($apiKey) .
            '&route=' . urlencode($route) .
            '&variables_values=' . urlencode($variablesValues) .
            '&flash=' . urlencode($flash) .
            '&sender_id=' . urlencode($sender_id) .
            '&message=' . urlencode($message) .
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
        // $sentotp = $input['otp'];
        $ot = 'otp-' . $sentMobile;
        $sentOTP = $input['otp'];
        if ($userOTP == $sentOTP) {

            $model = new UserModel();
            $this->session->remove($ot);
            $user = $model->findUserByUserNumber1($input['mobile_number']);

            if ($user == 0) {

                $response = $this->response->setStatusCode(200)->setBody('user not found');
                return $response;
            } else {
                return $this->getJWTForUser($input['mobile_number']);
                // return $this->getJWTForUser($input['mobile_number']);
            }
        } else {
            // OTP does not match, return false
            $response = $this->response->setStatusCode(200)->setBody('otp in valid');
            return $response;
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

            $snew = $model->save($input);
            // echo "<pre>"; print_r($snew); echo "</pre>";

            $foruid = $model->findUserByUserNumber($input['mobile_number']);
            // echo "<pre>";
            // print_r($foruid);
            // echo "</pre>";

            if ($input['role'] == 'Employers') {
                // echo "<pre>";
                // print_r("h");
                // echo "</pre>";
                // die();

                // $required_fields = ['name', 'gender', 'mobile_number', 'company_details', 'profile_picture', 'address', 'city', 'country', 'gst_number', 'field_of_company', 'contact_information'];
                // foreach ($required_fields as $field) {
                //     if (!isset($input[$field]) || empty($input[$field])) {
                //         return "Error: Missing required field '$field'";
                //     }
                // }
                $data = $input;
                $data['user_id'] = $foruid['id'];

                $user1 = $model->save_hprofile($data);
                $userd = $model->getHUserData($data['user_id']);
            } elseif ($input['role'] == 'Agent') {

                $data = $input;
                $data['user_id'] = $foruid['id'];

                $user1 = $model->save_Aprofile($data);
                $userd = $model->getAUserData($data['user_id']);
            } else {

                $data = $input;
                $data['user_id'] = $foruid['id'];
                $required_fields = ['user_id', 'name'];
                foreach ($required_fields as $field) {
                    if (!isset($data[$field]) || empty($data[$field])) {
                        return "Error: Missing required field '$field'";
                    }
                }
                $user1 = $model->save_profile($data);
                $userd = $model->getUserData($data['user_id']);
            }

            return $this
                ->getResponse(
                    [
                        'message' => 'User Register successfully',
                        'user' => $userd,
                        'status' => 'success',

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

    public function user_update()
    {

        try {
            $model = new UserModel();

            $input = $this->getRequestInput($this->request);

            $id = $input['user_id'];
            $required_fields = ['user_id', 'name', 'last_name', 'gender', 'email', 'state', 'city', 'country', 'created_at'];
            foreach ($required_fields as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    return "Error: Missing required field '$field'";
                }
            }
            $model->update_profile($id, $input);
            $post = $model->getUserData($id);

            return $this->getResponse(
                [
                    'message' => 'user updaetd successfully',
                    'user' => $post,
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
    public function user_updateaa()
    {

        try {
            $model = new UserModel();

            $input = $this->getRequestInput($this->request);

            $id = $input['user_id'];
            $required_fields = ['user_id', 'name', 'last_name', 'gender', 'email', 'state', 'city', 'country', 'points'];
            foreach ($required_fields as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    return "Error: Missing required field '$field'";
                }
            }

            $data['point'] = $input['points'];


            $model->update_profile($id, $input);


            $model->update_ref($id, $data);
            //             echo "test";
            // die();
            $post = $model->getUserData($id);
            // Set a flash data message (if you want to use server-side redirect)
            session()->setFlashdata('success', 'User updated successfully.');

            // Redirect with JavaScript
            echo "<script>
    alert('User updated successfully.');
    window.location.href = '/public/user-list#';
</script>";
            exit;
        } catch (Exception $exception) {

            return $this->getResponse(
                [
                    'message' => $exception->getMessage()
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function Huser_update()
    {

        try {
            $model = new UserModel();

            $input = $this->getRequestInput($this->request);

            $id = $input['user_id'];

            $model->update_hprofile($id, $input);

            $post = $model->getHUserData($id);

            return $this->getResponse(
                [
                    'message' => 'hotelior updaetd successfully',
                    'user' => $post,
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
    public function Auser_update()
    {

        try {
            $model = new UserModel();

            $input = $this->getRequestInput($this->request);

            $id = $input['user_id'];

            $model->update_Aprofile($id, $input);

            $post = $model->getUserAData($id);

            return $this->getResponse(
                [
                    'message' => 'Agent updaetd successfully',
                    'user' => $post,
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
    public function user_update_web()
    {

        try {
            $model = new UserModel();

            $input = $this->getRequestInput($this->request);
            $id = $input['user_id'];
            $required_fields = ['user_id', 'name', 'last_name', 'gender', 'email', 'state', 'city', 'country', 'created_at'];
            foreach ($required_fields as $field) {
                if (!isset($input[$field]) || empty($input[$field])) {
                    return "Error: Missing required field '$field'";
                }
            }
            $model->update_profile($id, $input);
            $post = $model->findUserById($id);
            return redirect()->to('user-list');
        } catch (Exception $exception) {

            return redirect()->to('user-list')->with('error', 'Failed to user update.');
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

            if ($userd == null) {
                $userd = $model->getUserAData($user['id']);
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

            // echo "test";


            // unset('1234');
            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $userd,
                        'status' => "success",

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
