<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class JobAppModel extends Model
{
    protected $table = 'job_applications';

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
    public function getJobData($userId)
    {
        
        $builder = $this->db->table('job_listings');
        $builder->select(' job_listings.*');
       
        $builder->where('job_listings.hotelier_id', $userId);
        $query = $builder->get();



        // Get the result
        $user = $query->getResult();
        
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        die();
        // Check if user data is found
        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
   



    
    public function findJobAppById(string $id)
    {

        $user = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
    public function findJobAppById1(string $id)
    {

        $user = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();


// echo $user;

        if (!$user) {
            return 0;
        } else {
            return 1;
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
   

    //Insert data
    public function saved($data): bool
    {

        $job_id = $data['job_id'];
        $candidate_id = $data['candidate_id'];
        $status = 'process';
      
      
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `job_applications`( `job_id`, `candidate_id`, `status`, `created_at`, `updated_at`) VALUES ('$job_id','$candidate_id','$status','$date1','$date1')";
        


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

    
//Update
    public function update1($id): bool
    {
        // echo $id;
        

         $status = ' In Review';
        $sql = "UPDATE `job_applications` SET  
        status = '$status'
          WHERE id = $id";
    // print_r($sql);
        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
   

}