<?php
namespace App\Controllers;
use App\Models\Apost;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Session\Session;
use ReflectionException;

class A_post extends BaseController
{
    use ResponseTrait;
    protected $session;

    public function index()
    {
        $model = new Apost();
        $post = $model->getallPostData();
        return $this->getResponse(
            [
                'message' => 'Post retrieved successfully',
                'post' => $post,
                'status' => 'success'
            ]
        );
    }

    public function store()
    {
        $input = $this->getRequestInput($this->request);
        $model = new Apost();
        $post = $model->save($input);
        return $this->getResponse(
            [
                'message' => 'Post  added successfully',
                'post' => $post,
                'status' => 'success'

            ]
        );
    }

    public function show($id)
    {
        // user_id pass
        try {
            $model = new Apost();
            $post = $model->getPostDataid($id);



            return $this->getResponse(
                [
                    'message' => 'Post retrieved successfully',
                    'Post' => $post,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Post for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }
    public function user_show($id)
    {
        // user_id pass
        try {
            $model = new Apost();
            $post = $model->getPostData($id);
            // $model1 = new UserModel();
            // $hotel = $model1->getHUserData($id);
            return $this->getResponse(
                [
                    'message' => 'Post retrieved successfully',
                    'Post' => $post,
                    // 'hotel' => $hotel ,
                    'status' => 'success'
                ]
            );
        } catch (Exception $e) {
            return $this->getResponse(
                [
                    'message' => 'Could not find Post for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    public function update($id)
    {
        try {
            $model = new Apost();
            $input = $this->getRequestInput($this->request);
            $model->update1($id, $input);
            $post = $model->findPostById($id);
            return $this->getResponse(
                [
                    'message' => 'Post  updaetd successfully',
                    'Post' => $post,
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
            $model = new Apost();
            $model->deletedata($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Post deleted successfully',
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
