<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class Job_prefModel extends Model
{
    protected $table = 'Job_pref';

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
        
        $builder = $this->db->table('Job_pref');
        $builder->select(' Job_pref.*');
       
        $builder->where('Job_pref.hotelier_id', $userId);
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
            throw new Exception('Job does not found');
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

        $department = $data['department'];
        $sub_department = $data['sub_department'];
        
    
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `Job_pref`( `department`, `sub_department`, `created_at`, `updated_at`) VALUES ('$department','$sub_department''$date1','$date1')";


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


        $sql = "UPDATE `Job_pref` SET  
        
        `department` = $department,
        `sub_department` = $sub_department,
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
}
