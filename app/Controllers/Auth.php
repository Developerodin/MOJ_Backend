<?php

namespace App\Controllers;

use App\Models\UserModel;

use App\Models\BasicModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

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
        $model = new UserModel();

        $user = $model->findUserByUserNumber1($input['mobile_number']);
        // echo "<pre>"; print_r($user); echo "</pre>";
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
        $model = new UserModel();

        $user = $model->findUserByUserNumber1($input['mobile_number']);

        if ($user == 0) {

            $snew = $model->save($input['mobile_number']);
            // echo "<pre>"; print_r($snew); echo "</pre>";

            $foruid = $model->findUserByUserNumber($input['mobile_number']);
            echo "<pre>";
            print_r($foruid);
            echo "</pre>";

            if ($input['role'] == 'Hoteliers') {
                $data = [

                    'user_id' => $foruid['id'],
                    
                    'name' => $input['name'],
                    'mobile_number' => $input['mobile_number'],
                    'email' => $input['email'],
                    'role' => $input['role'],
                    'profile_picture' => $input['profile_picture'],
                    'address' => $input['address'],
                    'city' => $input['city'],
                    'country' => $input['country'],
                    'interested_fields' => $input['interested_fields'],
                    'other_personal_details' => $input['other_personal_details'],
                ];

                $user1 = $model->save_hprofile($data);
            } else {

                $data = [

                    'user_id' => $foruid['id'],
                    'mobile_number' => $input['mobile_number'],
                    'full_name' => $input['full_name'],
                    'email' => $input['email'],
                    'role' => $input['role'],
                    'profile_picture' => $input['profile_picture'],
                    'address' => $input['address'],
                    'city' => $input['city'],
                    'country' => $input['country'],
                    'interested_fields' => $input['interested_fields'],
                    'other_personal_details' => $input['other_personal_details'],
                ];

                $user1 = $model->save_profile($data);
            }

            $data1 = $model->findUserByUserNumber($input['mobile_number']);
            $data['user_id'] = $data1['user_id'];

            // echo json_encode( $wallet );
            // die();
        } else {
            $user1 = null;
            $response =
                $this->response->setStatusCode(500)->setBody('user allrady in list');
            return $response;
        }
        if ($user1 == null) {
            $response = $this->response->setStatusCode(400)->setBody('user not listed');
            return $response;
        } else {

            return $this->getJWTForNewUser(
                $data['user_number'],
                ResponseInterface::HTTP_CREATED
            );
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

            echo "<pre>";
            print_r($userd);
            echo "</pre>";
            die();

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $userd,

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
