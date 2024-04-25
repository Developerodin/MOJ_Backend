<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class resumeSearchModel extends Model
 {
//find by user id
    public function getby_id_data( $userId )
 {

        $builder = $this->db->table( 'user_profile' );
      
        $builder->select( 'resume' );
      
        $builder->where( 'user_id', $userId );

        
        $query = $builder->get();
        $result = $query->getResult();

     
        if ( $result ) {
            // Fetch the result set as an array of objects
            return $result;
        } else {
            // No rows found, return null or an empty array, depending on your preference
            return null;
        }
    }

    public function findResumeById( $id )
 {
        $user = $this
        ->asArray()
        ->where( [ 'id' => $id ] )
        ->first();

        if ( !$user )
        throw new Exception( 'Resume not exist for specified user id' );

        return $user;
    }

    


    public function findResumeById1( string $id )
 {

        $user = $this
        ->asArray()
        ->where( [ 'id' => $id ] )
        ->first();

        if ( !$user ) {
            return null;
        } else {
            return $user;
        }
    }


    //update
    public function update( $id, $data ): bool
 {

        if ( empty( $data ) ) {
            echo '1';
            return true;
        }

        // $name = $data[ 'name' ];

        $mobile_number = $data[ 'mobile_number' ];
        $status = $data[ 'status' ];
        $sql = "UPDATE `users` SET  
  
        mobile_number = '$mobile_number',
        status = '$status'
          WHERE id = $id";
        // echo '<pre>';
        print_r( $sql );
        // echo '</pre>';
        $post = $this->db->query( $sql );
        if ( !$post )
        throw new Exception( 'Post does not exist for specified id' );

        return $post;
    }


    //delete
    public function delete( $id )
 {
        $sql = "DELETE FROM `Resume` WHERE id= '$id'";

        $post = $this->db->query( $sql );

        if ( !$post ) {
            return false;
        } else {
            return $post;
        }
    }
}
