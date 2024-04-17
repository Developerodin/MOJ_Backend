<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;


   
class initialModel extends Model
{

    public function saved($data): bool
    {

        $job_id = $data['job_id'];
        $candidate_id = $data['candidate_id'];
        $status = 'process';
      
      
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `init`( `job_id`, `candidate_id`, `status`, `created_at`, `updated_at`) VALUES ('$job_id','$candidate_id','$status','$date1','$date1')";
        


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post){
            return null;
        }else{
            return $post;
        }
            

        
    }

    

}