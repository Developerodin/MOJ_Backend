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
    protected $userProfileTable = 'user_profiles';
    // protected $allowedFields = [
    //     'name',
    //     'email',
    //     'retainer_fee'
    // ];
    protected $db;
    protected $updatedField = 'updated_at';

  
    public function getUserProfileEmptyFields($user_id)
    {


        $builder = $this->db->table('user_profiles');
        $builder->select('*'); // Use '*' to select all columns
        $builder->where('user_profiles.user_id', $user_id);
        $query = $builder->get();
        
      $user_sql =  $query->getResult();


      print_r($user_sql);

        $query = $this->db->table($this->userProfileTable)
                        ->selectCount([
                            'name_empty' => 'SUM(CASE WHEN name IS NULL OR name = "" THEN 1 ELSE 0 END)',
                            'last_name_empty' => 'SUM(CASE WHEN last_name IS NULL OR last_name = "" THEN 1 ELSE 0 END)',
                            'gender_empty' => 'SUM(CASE WHEN gender IS NULL OR gender = "" THEN 1 ELSE 0 END)',
                            'email_empty' => 'SUM(CASE WHEN email IS NULL OR email = "" THEN 1 ELSE 0 END)',
                            'role_empty' => 'SUM(CASE WHEN role IS NULL OR role = "" THEN 1 ELSE 0 END)',
                            'address_empty' => 'SUM(CASE WHEN address IS NULL OR address = "" THEN 1 ELSE 0 END)',
                            'pin_code_empty' => 'SUM(CASE WHEN pin_code IS NULL THEN 1 ELSE 0 END)',
                            'dob_empty' => 'SUM(CASE WHEN dob IS NULL THEN 1 ELSE 0 END)',
                            'state_empty' => 'SUM(CASE WHEN state IS NULL OR state = "" THEN 1 ELSE 0 END)',
                            'city_empty' => 'SUM(CASE WHEN city IS NULL OR city = "" THEN 1 ELSE 0 END)',
                            'country_empty' => 'SUM(CASE WHEN country IS NULL OR country = "" THEN 1 ELSE 0 END)',
                        ])
                        ->where('user_id', $user_id);
    
        // Debugging: Print the generated SQL query
        echo $this->db->getLastQuery();
    
        $result = $query->get()->getRow();
    
        // Debugging: Print the result of the query
        print_r($result);
    
        return $result;
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
    public function all_state()
    {
        $builder = $this->db->table('all_states');
        $builder->select('*'); // Use '*' to select all columns
        
        $query = $builder->get();
        
        return $query->getResult();
    }
    public function city_state_state($Id)
    {
        $builder = $this->db->table('all_cities');
        $builder->select(' all_cities.*');
        $builder->where('all_cities.state_code', $Id);
        $query = $builder->get();

        return $query->getResult();
       
    }
    public function all_state1()
    {
        $builder = $this->db->table('unified_pincodes');
        $builder->select('*');
        
        $query = $builder->get();

        return $query->getResult();
       
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