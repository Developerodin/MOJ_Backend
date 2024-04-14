<?php

namespace App\Controllers;
use App\Models\PostModel;
use App\Models\SPostModel;
use App\Models\BannerModel;
use App\Models\PostTypeModel;
use App\Models\SPostTypeModel;
use App\Models\UserModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

use ReflectionException;
class Users extends BaseController
{
   
   
// ---- banner ===//
    public function banners()
    {
       
        $model = new BannerModel();
        return $this->getResponse(
            [
                'message' => 'Banner retrieved successfully',
                'post' => $model->findAll()
            ]
        );
    }
    public function banner_add()
    {
        $input = $this->getRequestInput($this->request);
        $image= $input['image'];
        $model = new BannerModel();
        $model->save_img($image);
              
        return $this->getResponse(
            [
                'message' => 'Banner  added successfully',
                
            ]
        );
    }
    public function destroy_banner($id)
    {
        try {
            $model = new BannerModel();
            $post = $model->findBannerById($id);
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
    public function update_banner($id)
    {
        try {
            $model = new BannerModel();
            $input = $this->getRequestInput($this->request);
            $model->update1($id ,$input);
            $post = $model->findBannerById($id);
            return $this->getResponse(
                [
                    'message' => 'banner updated successfully',
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
    
// -------- start g section -------//
//game type
    

    
    // g deleted the
    public function destroy($id)
    {
        try {
            $model = new UserModel();
            $post = $model->findPostById($id);
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
