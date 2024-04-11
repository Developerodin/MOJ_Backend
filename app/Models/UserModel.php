<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;
class UserModel extends Model
{
    protected $table = 'users';
   
    protected $allowedFields = [
        'mobile_number',
        'otp',
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
    public function user_a($id)
    {
      
        $userId = $id; // Replace with the desired user_id

        $builder = $this->db->table('users');
        $builder->select('users.*, wallet.*, transactions.*');
        $builder->join('wallet', 'users.user_id = wallet.user_id', 'inner');
        $builder->join('transactions', 'wallet.wallet_id = transactions.wallet_id', 'inner');
        $builder->where('users.user_id', $userId); // Replace 1 with the desired user_id
        $query = $builder->get();
      
        echo "1";
        $user = $query->getResult();
        
        if (!$user){
            return null;
           
        } else{
            return $user;
        }
        
    }
    public function findUserByUserNumber(string $mobile_number)
    {
       
        $user = $this
            ->asArray()
            ->where(['mobile_number' => $mobile_number])
            ->first();
            
        if (!$user){
            return null;
           
        } else{
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

            if (! empty($eventData['returnData'])) {
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
    public function findUserByUserNumber1(string $mobile_number)
    {
       
        $user = $this
            ->asArray()
            ->where(['mobile_number' => $mobile_number])
            ->first();
            
        if (!$user){
            return 0;
           
        } else{
            return 1;
        }
            

       
    }
    public function findUserById($id)
    {
        $user = $this
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$user) 
            throw new Exception('User does not exist for specified user id');

        return $user;
    }
    
    public function save($data): bool
    {
        
    $mobile_number = $data['mobile_number'];
    $otp= $data['otp'];
    $role= $data['role'];
    $status = "1";
    
    
    $date = new DateTime();
    $date = date_default_timezone_set('Asia/Kolkata');

    $date1 = date("m-d-Y h:i A");
    $sql = "INSERT INTO `users`(`mobile_number`, `otp`, `role`, `status`, `created_at`, `updated_at`) VALUES ('$mobile_number',' $otp','$role','$status','$date1','$date1')";
        $post = $this->db->query($sql);
        // echo json_encode($post);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
   
   
    
    public function admin_update($id ,$data): bool
    {

      // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $pin = $data['pin'];
       
        $sql = "UPDATE `admin` SET  
        pin = '$pin'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
    public function update1($id ,$data): bool
    {

      // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $user_name = $data['user_name'];
      
        $mobile_number = $data['mobile_number'];
        $status = $data['status'];
        $sql = "UPDATE `users` SET  
        user_name = '$user_name',
        mobile_number = '$mobile_number',
        status = '$status'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
    public function update_a($id ,$data): bool
    {

      // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $status = $data['status'];
        $sql = "UPDATE `users` SET  
        status = '$status'
          WHERE user_id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('Post does not exist for specified id');

    return $post;

       
    }
    public function update_otp($id ,$data): bool
    {

      // echo $id;

        if (empty($data)) {
            echo "1";
            return true;
        }

        $pin = $data['otp'];
        $sql = "UPDATE `users` SET  
        otp = '$pin'
          WHERE user_id = $id";
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



   
  
   
    
 