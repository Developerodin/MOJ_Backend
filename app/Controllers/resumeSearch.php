<?php

namespace App\Controllers;
use App\Models\JobModel;
use App\Models\JobAppModel;
use App\Models\UserModel;
use App\Models\resumeSearchModel;

use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use \DateTime;

use ReflectionException;

class resumeSearch extends BaseController
 {

    //update
    public function update( $id )
 {

        $model = new resumeSearchModel();
        $input = $this->getRequestInput( $this->request );
        $post = $model->findResumeById( $id );

        if ( $post == 0 ) {
            $response =
            $this->response->setStatusCode( 400 )->setBody( 'Resume not exist for specified user id' );
            return $response;
        } else {

            $post = $model->update1( $id );
            $post = $model->findResumeById1( $id );
            return $this->getResponse(
                [
                    'message' => 'Resume updated successfully',
                    'client' => $post
                ]
            );

        }
    }


    //Display resume by id 
    public function show( $id )
    {
        try {
            $model = new resumeSearchModel();
            $post = $model->getby_id( $userId );
            return $this->getResponse(
                [
                    'message' => 'Resume found successfully',
                    'Job' => $post
                ]
            );
        } catch ( Exception $e ) {
            return $this->getResponse(
                [
                    'message' => 'Could not find resume for specified ID'
                ],
                ResponseInterface::HTTP_NOT_FOUND
            );
        }
    }

    //delete
    public function destroy($id)
    {
        try {
            $model = new resumeSearchModel();
            $model->delete($id);
            return $this
                ->getResponse(
                    [
                        'message' => 'Resume deleted successfully',
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


