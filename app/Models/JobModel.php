<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class JobModel extends Model
{
    protected $table = 'job_listings';

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
            return null;
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

        $hotelier_id = $data['hotelier_id'];
        $job_title = $data['job_title'];
        $job_description = $data['job_description'];
        $job_type = $data['job_type'];
        $skill_requirements = $data['skill_requirements'];
        $location = $data['location'];
        $department = $data['department'];
        $experience_requirements = $data['experience_requirements'];
        $status = '1';
    
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `job_listings`( `hotelier_id`, `job_title`, `job_description`, `job_type`, `skill_requirements`, `location`, `department`, `experience_requirements`, `created_at`, `updated_at`, `status`) VALUES ('$hotelier_id','$job_title','$job_description','$job_type','$skill_requirements','$location','$department','$experience_requirements','$date','$date1','$status')";


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }

    public function update1($id, $data): bool
    {

        // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $hotelier_id = $data['hotelier_id'];
        $job_title = $data['job_title'];
        $job_description = $data['job_description'];
        $job_type = $data['job_type'];
        $skill_requirements = $data['skill_requirements'];
        $location = $data['location'];
        $department = $data['department'];
        $experience_requirements = $data['experience_requirements'];
        $status = '1';
        $sql = "UPDATE `user_log` SET  
        $hotelier_id = '$hotelier_id',
        $job_title = '$job_title',
        $job_description = '$job_description',
        $job_type = '$job_type',
        $skill_requirements = '$skill_requirements',
        $location = '$location',
        $department = '$department',
        $experience_requirements = '$experience_requirements',
       
        status = '$status'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function update_a($id, $data): bool
    {

        // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $status = $data['status'];
        $sql = "UPDATE `user_log` SET  
        status = '$status'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
    public function update_pin($id, $data): bool
    {

        // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $pin = $data['pin'];
        $sql = "UPDATE `user_log` SET  
        pin = '$pin'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }
}
