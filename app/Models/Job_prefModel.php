<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class Job_prefModel extends Model
{
    protected $table = 'job_pref';

    protected $allowedFields = [
        'mobile_number',

    ];
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data): array
    {

        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
        return $data;
    }

    private function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }
    

    /// get user information
    public function getsubData($department)
    {
        
        $builder = $this->db->table('job_pref');
        $builder->select(' job_pref.*');
       
        $builder->where('job_pref.department', $department);
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();
        
        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die();
        // Check if user data is found
        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
    public function getuser_job()
    {
        
        $builder = $this->db->table('job_pref_user');
        $builder->select('*');
       
       
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();
        
        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die();
        // Check if user data is found
        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
    public function show_userid($id)
    {
        
        $builder = $this->db->table('job_pref_user');
        $builder->select(' job_pref_user.*');
       
        $builder->where('job_pref_user.user_id', $id);
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();
        
        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die();
        // Check if user data is found
        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
    public function show_uid($id)
    {
        
        $builder = $this->db->table('job_pref_user');
        $builder->select(' job_pref_user.*');
       
        $builder->where('job_pref_user.id', $id);
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();
        
        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die();
        // Check if user data is found
        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
   



    
    public function findJobById(string $id)
    {

        $user = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$user) {
            throw new Exception('Job pref does not found');
        } else {
            return $user;
        }
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        if ($this->tempAllowCallbacks) {
            // Call the before event and check for a return
            $eventData = $this->trigger('beforeFind', [
                'method'    => 'findAll',
                'limit'     => $limit,
                'offset'    => $offset,
                'singleton' => false,
            ]);

            if (!empty($eventData['returnData'])) {
                return $eventData['data'];
            }
        }

        $eventData = [
            'data'      => $this->doFindAll($limit, $offset),
            'limit'     => $limit,
            'offset'    => $offset,
            'method'    => 'findAll',
            'singleton' => false,
        ];

        if ($this->tempAllowCallbacks) {
            $eventData = $this->trigger('afterFind', $eventData);
        }

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;
        $this->tempAllowCallbacks = $this->allowCallbacks;

        return $eventData['data'];
    }
   


    public function save($data): bool
    {
// echo "test";
        $department = $data['department'];
        $sub_department = $data['sub_department'];
        
    
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `job_pref`( `department`, `sub_department`, `created_at`, `updated_at`) VALUES ('$department','$sub_department','$date1','$date1')";


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post){
            throw new Exception('Job does not save');
        }
           

        return $post;
    }

    public function update1($id, $data): bool
    {

        // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $department = $data['department'];
        $sub_department = $data['sub_department'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');


        $sql = "UPDATE `job_pref` SET  
        
        `department` = '$department',
        `sub_department` = '$sub_department',
        updated_at = '$date1'
          WHERE id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function deletedata($id)
    {
        $post = $this
            ->asArray()
            ->where(['id' => $id])
            ->delete();

        if (!$post) 
            throw new Exception('user does not exist for specified id');

        return $post;
    }


    // user

    public function user_save($data): bool
    {
// echo "test";

        $user_id = $data['user_id'];
        $department = $data['department'];
        $job_type = $data['job_type'];
        $pref_state = $data['pref_state'];
        $pref_city = $data['pref_city'];
        $sub_dep = $data['sub_dep'];
        $salery = $data['salery'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        
    
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `job_pref_user`( `user_id`,`job_type`,`department`,`sub_dep`, `pref_state`,`pref_city`,`salery`,`start_time`,`end_time`,`created_at`, `updated_at`) VALUES ('$user_id','$job_type','$department','$sub_dep','$pref_state','$pref_city','$salery','$start_time','$end_time','$date1','$date1')";


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post){
            throw new Exception('Job does not save');
        }
           

        return $post;
    }

    public function user_update1($id, $data): bool
    {

        // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

       
        $department = $data['department'];
        $job_type = $data['job_type'];
        $pref_state = $data['pref_state'];
        $pref_city = $data['pref_city'];
        $sub_dep = $data['sub_dep'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $salery = $data['salery'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');


        $sql = "UPDATE `job_pref_user` SET  
        
        
        `department` = '$department',
        `job_type` ='$job_type',
        `pref_state` = '$pref_state',
        `pref_city` = '$pref_city',
        `salery` = '$salery',
        `sub_dep` = '$sub_dep',
        `start_time` = '$start_time',
        `end_time` = '$end_time',
        updated_at = '$date1'
          WHERE id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";

        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function user_update11($id, $data): bool
    {

        // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

       
        $department = $data['department'];
        $job_type = $data['job_type'];
        $pref_state = $data['pref_state'];
        $pref_city = $data['pref_city'];
        $sub_dep = $data['sub_dep'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $salery = $data['salery'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');


        $sql = "UPDATE `job_pref_user` SET  
        
        
        `department` = '$department',
        `job_type` ='$job_type',
        `pref_state` = '$pref_state',
        `pref_city` = '$pref_city',
        `sub_dep` = '$sub_dep',
        `salery` = '$salery',
        `start_time` = '$start_time',
        `end_time` = '$end_time',
        updated_at = '$date1'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";

        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function user_deletedata($id)
    {
         // Prepare the SQL statement with a placeholder for the id
         $sql = "DELETE FROM `job_pref_user` WHERE id = ?";
        
         // Execute the prepared statement with the id parameter
         $post = $this->db->query($sql, [$id]);
     
         // Check if the query was executed successfully
         if (!$post) {
             // If the query fails, return false
             return false;
         } else {
             // If the query succeeds, return true
             return true;
         }
    }
}
