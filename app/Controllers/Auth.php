<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminUserModel;
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
    public function First_login()
    {
       $input = $this->getRequestInput($this->request);
    //    echo "<pre>"; print_r($input); echo "</pre>";
    //    die();
       $model = new UserModel();
        
       $user = $model->findUserByUserNumber1($input['mobile_number']);
        // echo "<pre>"; print_r($user); echo "</pre>";
        //    die();
       if($user == 0){
        $data =[
            
            'role' => $input['role'],
            'mobile_number' => $input['mobile_number'],
            'otp' => $input['otp'],
        ];
       
      
        $user1 = $model->save($data);         
        $data1 = $model->findUserByUserNumber($input['mobile_number']);
        $data['id'] = $data1['id'];
       
       
        // echo json_encode( $wallet );
        // die();
        }else{
            $user1 = null;
            $response = $this->response->setStatusCode(500)->setBody('user allrady in list');
            return  $response;
         }
        if($user1 == null){
            $response = $this->response->setStatusCode(400)->setBody('user not listed');
            return  $response;
              
        }else{

            return $this->getJWTForNewUser(
                $data['mobile_number'],
                ResponseInterface::HTTP_CREATED
            );
        } 
    }
    public function register()
    {
       $input = $this->getRequestInput($this->request);
    //    echo "<pre>"; print_r($input); echo "</pre>";
    //    die();
       $model = new UserModel();
        
       $user = $model->findUserByUserNumber1($input['mobile_number']);
        // echo "<pre>"; print_r($user); echo "</pre>";
        //    die();
       if($user == 0){
        $data =[
            
            'role' => $input['role'],
            'mobile_number' => $input['mobile_number'],
            'otp' => $input['otp'],
        ];
       
      
        $user1 = $model->save($data);         
        $data1 = $model->findUserByUserNumber($input['mobile_number']);
        $data['id'] = $data1['id'];
       
       
        // echo json_encode( $wallet );
        // die();
        }else{
            $user1 = null;
            $response = $this->response->setStatusCode(500)->setBody('user allrady in list');
            return  $response;
         }
        if($user1 == null){
            $response = $this->response->setStatusCode(400)->setBody('user not listed');
            return  $response;
              
        }else{

            return $this->getJWTForNewUser(
                $data['mobile_number'],
                ResponseInterface::HTTP_CREATED
            );
        } 
    }
     
    public function admin_register()
    {


        // $rules = [
        //     'user_name' => 'required',
        //     'pin' => 'required|min_length[4]'
        // ];
      
       $input = $this->getRequestInput($this->request);

       $model1 = new AdminUserModel();
                    
       $user = $model1->findUserByUserNumber1($input['mobile_number']);
    //    echo "<pre>"; print_r($user); echo "</pre>";
    //    die();
       if($user == 0){
        $data =[
            
            'user_name' => $input['user_name'],
            'mobile_number' => $input['mobile_number'],
            'pin' => password_hash($input['pin'], PASSWORD_DEFAULT),
        ];
       
      
        $userModel = new UserModel();  
        $user_admin = $userModel->save1($data);

        }else{
            $user_admin = null;
              $response = $this->response->setStatusCode(400)->setBody('user allrady in list');
            return  $response;
           
          
         }
       
        if($user_admin == null){
              $response = $this->response->setStatusCode(400)->setBody('not register');
            return  $response;
             
        }else{
            return $this->getJWTForNewUser(
                $data['mobile_number'],
                ResponseInterface::HTTP_CREATED
            );
        }

           
      
    }
    /**
     * Authenticate Existing User
     * @return Response
     */
    public function login()
    {
        $rules = [

            'pin' => 'required|min_length[4]|max_length[4]|validateUser[mobile_number, pin]'
        ];

        $errors = [
            'pin' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);
        // echo json_encode($input);
        if($this->validateRequest($input, $rules, $errors)){
           
            return $this->getJWTForUser($input['mobile_number']);
        }else{
            // return $this->getResponse($input);
              $response = $this->response->setStatusCode(400)->setBody('Invalid login Mobile Number');
            return  $response;
        }
        
       
       
    }
    public function admin_login()
    {
      
        $rules = [
           
            'pin' => 'required|min_length[4]|max_length[4]|validateUser[mobile_number, pin]'
        ];

        $errors = [
            'pin' => [
                'validateUser' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);
        // echo json_encode($input);
        if($this->validateRequest1($input, $rules, $errors)){
            // return $this->getResponse($input);
            return $this->getJWTForAdminUser($input['mobile_number']);
        }else{
            $response = $this->response->setStatusCode(400)->setBody('Invalid login credentials provided');
            return  $response;
        }
        
       
       
    }
    public function user_update($id)
    {
        try {
            $model = new UserModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id ,$input);
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
    public function user_otp($id)
    {
        try {
            $model = new UserModel();
            $input = $this->getRequestInput($this->request);
            //   echo "<pre>"; print_r($input);
            // echo "</pre>";
            // die();

            if($this->validatepin($input, $id)){
                // return $this->getResponse($input);
                $data =[
                    'otp' => $input['otp'],
                ];
                $model->update_pin($id ,$data);
            }else{
                $response = $this->response->setStatusCode(400)->setBody('Invalid provided Old Password');
                return  $response;
            }

           
            $post = $model->findUserById($id);
            return $this->getResponse(
                [
                    'message' => 'user pin updaetd successfully',
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
    public function auser_up_pin($id)
    {
        try {
            $model = new UserModel();
            $input = $this->getRequestInput($this->request);
            $data =[
                'pin' => password_hash($input['newpin'], PASSWORD_DEFAULT),
            ];
            $model->update_pin($id ,$data);
           
            $post = $model->findUserById($id);
            return $this->getResponse(
                [
                    'message' => 'user pin updaetd successfully',
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
    public function adminuser_update($id)
    {
        try {
            $model = new UserModel();
          $input = $this->getRequestInput($this->request);
          $data =[
            'pin' => password_hash($input['newpin'], PASSWORD_DEFAULT),
        ];
            $model->admin_update($id ,$data);
              
            // $post = $model->findUserById($id);
            return $this->getResponse(
                [
                    'message' => 'user updaetd successfully'
                    
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
        string $mobile_number,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        
        try {
            $model = new UserModel();
            
            $user = $model->findUserByUserNumber($mobile_number);
           
            $cart = $model->findUById($user['id']);
          
           
            // echo json_encode($user);
            unset($user['otp']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                        
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
    private function getJWTForAdminUser(
        string $mobile_number,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        
        try {
            $model = new AdminUserModel();
            $user = $model->findUserByUserNumber($mobile_number);
           
            unset($user['otp']);

            helper('jwt');

            return $this
                ->getResponse(
                    [
                        'message' => 'User authenticated successfully',
                        'user' => $user,
                       
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
    private function getJWTForNewUser(
        string $mobile_number,
        int $responseCode = ResponseInterface::HTTP_OK
    )
    {
        
        try {
            $model = new UserModel();
            $user = $model->findUserByUserNumber($mobile_number);
            // echo json_encode($user);
            unset($user['otp']);

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
