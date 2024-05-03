<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;
use \Datetime;


// structure

//save
//find by id--
//find all
// deletes by id
// activity
//update service    
class BasicModel extends Model
{
    protected $table = 'basic_table';
    // protected $allowedFields = [
    //     'name',
    //     'email',
    //     'retainer_fee'
    // ];
    protected $db;
    protected $updatedField = 'updated_at';

  
  
    
    
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

    public function save($data): bool
    {

        $whatsapp = $data['whatsapp'];
        $mobile = $data['mobile'];
        $email = $data['email'];
        $hiw = $data['hiw'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `basic_table`( `whatsapp`,`mobile`, `email`, `created_at`,`updated_at`) VALUES ('$whatsapp','$mobile','$email','$date1','$date1')";

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }

    public function update_num($data): bool
    {

        if (empty($data)) {
            echo "1";
            return true;
        }
      
        $whatsapp = $data['whatsapp'];
        $mobile = $data['mobile'];
        $email = $data['email'];
        $hiw = $data['hiw'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date('Y-m-d H:i:s');

        $sql = "UPDATE `basic_table` SET  
        whatsapp= '$whatsapp',
        email= '$email',
        mobile= '$mobile',
        hiw= '$hiw',
        updated_at= '$date1'
              
         WHERE id = 1";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
    if (!$post) 
        throw new Exception('basic detals does not exist for specified id');

    return $post;

       
    }
   
    public function delete_us($id)
    {
        // Prepare the SQL statement with a placeholder for the id
        $sql = "DELETE FROM `basic_table` WHERE id = ?";
        
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