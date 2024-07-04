<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class Apost extends Model
{
    protected $table = 'A_post';

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
    public function getallJobData()
    {
        $builder = $this->db->table('A_post');
        $builder->select('A_post.*, hoteliers.name,hoteliers.address,hoteliers.city,hoteliers.state,hoteliers.pin_code,hoteliers.address');
        $builder->join('hoteliers', 'hoteliers.user_id = A_post.hotelier_id');
        
        $query = $builder->get();
    
        // Get the result
        $result = $query->getResult();
    
        if (!$result) {
            return null;
        } else {
            return $result;
        }
    }
    public function getPostDataid($id)
    {
        $builder = $this->db->table('A_post');
        $builder->select('A_post.* ');
       
        $builder->where('A_post.id', $id);
        $query = $builder->get();
    
        // Get the result
        $result = $query->getResult();
    
        if (!$result) {
            return null;
        } else {
            return $result;
        }
    }
    public function getPostData($userId)
    {
        $builder = $this->db->table('A_post');
        $builder->select('A_post.*');
     
        $builder->where('A_post.user_id', $userId);
        $query = $builder->get();
    
        // Get the result
        $result = $query->getResult();
    
        if (!$result) {
            return null;
        } else {
            return $result;
        }
    }



    
    public function findPostById(string $id)
    {

        $user = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$user) {
            throw new Exception('Post does not found');
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
        if (empty($data)) {
            echo "1";
            return true;
        }
        $user_id = $data['user_id'];
        // $Hotel_name = $data['Hotel_name'];
        $preferred_state = $data['preferred_state'];
        $preferred_city = $data['preferred_city'];
        $staff_details = $data['staff_details'];
       
    
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `A_post`( `user_id`,`preferred_state`, `preferred_city`, `staff_details`,  `created_at`, `updated_at`) VALUES ('$user_id','$preferred_state','$preferred_city','$staff_details','$date1','$date1')";


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post){
            throw new Exception('Post does not save');
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

       // $Hotel_name = $data['Hotel_name'];
       $preferred_state = $data['preferred_state'];
       $preferred_city = $data['preferred_city'];
       $staff_details = $data['staff_details'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date('Y-m-d H:i:s');

        $sql = "UPDATE `A_post` SET       
       preferred_state= '$preferred_state',       
       preferred_city= '$preferred_city',
       staff_details= '$staff_details',       
        updated_at = '$date1'  WHERE id = $id";
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
            throw new Exception('Post not exist for specified id');

        return $post;
    }
}

