<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;

class MsgModel extends Model
{
    protected $table = 'messages';

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
    
    public function getallsendData($id)
    {
        $builder = $this->db->table('messages');
        $builder->select('messages.*');
        $builder->where('messages.sender_id', $id);
        
        $query = $builder->get();
    
        // Get the result
        $result = $query->getResult();
    
        if (!$result) {
            return null;
        } else {
            return $result;
        }
    }
    public function getallrecData($id)
    {
        $builder = $this->db->table('messages');
        $builder->select('messages.*');
        $builder->where('messages.receiver_id', $id);
        
        $query = $builder->get();
    
        // Get the result
        $result = $query->getResult();
    
        if (!$result) {
            return null;
        } else {
            return $result;
        }
    }
  
    
    public function findById(string $id)
    {

        $user = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$user) {
            throw new Exception('Msg does not found');
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
        $sender_id = $data['sender_id'];
        // $Hotel_name = $data['Hotel_name'];
        $receiver_id = $data['receiver_id'];
        $receiver_role = $data['receiver_role'];
        $sender_role = $data['sender_role'];
        $message_content = $data['message_content'];
        
    
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `messages`( `sender_id`,`receiver_id`,`sender_role`,`receiver_role`, `message_content`, `sent_at`) VALUES ('$sender_id','$receiver_id','$sender_role','$receiver_role','$message_content','$date1')";


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post){
            throw new Exception('Job does not save');
        }
           

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

