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
    public function get_data()
    {
        // echo "test";
        $builder = $this->db->table('working_experiences');
        $builder->select(' working_experiences.*');


        $query = $builder->get();

        $user = $query->getResult();

        $result = $user;
        return $result;
    }

    public function getby_id_data($userId)
    {

        // Create a query builder instance for the user_log table
        $builder = $this->db->table('working_experiences');

        // Set the SELECT clause to select all fields from the user_log table
        $builder->select('working_experiences.*');
        $builder->where('working_experiences.user_id', $userId);
        // Execute the query and retrieve the results
        $query = $builder->get();
        $result = $query->getResult();
        // Check if any rows were returned
        if ($result) {
            // Fetch the result set as an array of objects
            
            return $result;
        } else {
            // No rows found, return null or an empty array, depending on your preference
            return null;
        }
    }

    /// get user information
    public function getUserData($userId)
    {

        $builder = $this->db->table('user_profiles');
        $builder->select(' user_profiles.*');

        $builder->where('user_profiles.user_id', $userId);
        $query = $builder->get();

        $user = $query->getResult();


        if (!$user) {
            return null;
        } else {
            return $user[0];
        }
    }
    public function getUserHData($userId)
    {

        $builder = $this->db->table('hoteliers');
        $builder->select(' hoteliers.*');

        $builder->where('hoteliers.user_id', $userId);
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();

        // echo "<pre>";
        // print_r($user[0]);
        // echo "</pre>";
        // die();
        // Check if user data is found
        if (!$user) {
            return null;
        } else {
            return $user[0];
        }
    }




    public function findUserByUserNumber1(string $mobile_number)
    {
        // echo "test";
        // die();
        $user = $this
            ->asArray()
            ->where(['mobile_number' => $mobile_number])
            ->first();

        if (!$user) {
            return 0;
        } else {
            return 1;
        }
    }

    public function findUserByUserNumber(string $mobile_number)
    {

        $user = $this
            ->asArray()
            ->where(['mobile_number' => $mobile_number])
            ->first();

        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
    public function findUserByUserName(string $mobile_number)
    {

        $user = $this
            ->asArray()
            ->where(['mobile_number' => $mobile_number])
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

        $mobile_number = $data;
        // echo "<pre>"; print_r($mobile_number); echo "</pre>";
        // die();
        $status = "1";

        $points = '0';
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');

        $date1 = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `users`(`mobile_number`, `created_at`, `updated_at`, `last_active`, `points`, `status`) VALUES ('$mobile_number','$date1','$date1','$date1','$points','$status')";


        //     echo "<pre>"; print_r($sql); echo "</pre>";
        // die();

        $post = $this->db->query($sql);
        // echo json_encode($post);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }


    public function save_profile($data)
    {
        // echo json_encode($data);
        // $required_fields = ['user_id', 'name', 'resume', 'gender', 'email', 'profile_picture', 'address', 'city', 'country', 'interested_fields', 'other_personal_details'];
        // foreach ($required_fields as $field) {
        //     if (!isset($data[$field]) || empty($data[$field])) {
        //         return "Error: Missing required field '$field'";
        //     }
        // }
        $user_id = $data['user_id'];
        $name = $data['name'];
        $resume = $data['resume'];
        $gender = $data['gender'];
        $email = $data['email'];
        $profile_picture = $data['profile_picture'];
        $address = $data['address'];
        $city = $data['city'];
        $country = $data['country'];
        $interested_fields = $data['interested_fields'];
        $other_personal_details = $data['other_personal_details'];

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date = date("m-d-Y h:i A");
        $sql = "INSERT INTO `user_profiles`( `user_id`, `name`,`gender`, `email`, `profile_picture`, `address`, `city`, `country`, `interested_fields`, `other_personal_details`,`resume`, `created_at`, `updated_at`) VALUES ('$user_id','$name','$gender','$email','$profile_picture','$address','$city','$country','$interested_fields','$other_personal_details','$resume','$date','$date')";
        // echo json_encode($sql);
        // echo json_encode($data);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function save_workex($data)
    {
        // echo json_encode($data);

        $user_id = $data['user_id'];
        $organisation = $data['organisation'];
        $designation = $data['designation'];
        $profile = $data['profile'];
        $location = $data['location'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];


        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date = date("m-d-Y h:i A");
        $sql = "INSERT INTO `working_experiences`( `user_id`, `organisation`,`designation`, `profile`, `location`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES ('$user_id','$organisation','$designation','$profile','$location','$start_date','$end_date','$date','$date')";
        // echo json_encode($sql);
        // echo json_encode($data);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function save_hprofile($data)
    {
        // echo json_encode($data);

        $user_id = $data['user_id'];
        $name = $data['name'];

        $company_details = $data['company_details'];
        $address = $data['address'];
        $city = $data['city'];
        $role = $data['role'];
        $country = $data['country'];
        $gst_number = $data['gst_number'];
        $field_of_company = $data['field_of_company'];
        $profile_picture = $data['profile_picture'];
        $contact_information = $data['contact_information'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date = date("m-d-Y h:i A");
        $sql = "INSERT INTO `hoteliers`( `user_id`, `name`,  `company_details`, `address`, `city`, `role`, `country`, `gst_number`, `field_of_company`, `profile_picture`, `contact_information`, `created_at`, `updated_at`) VALUES ('$user_id','$name',' $company_details','$address','$city','$role','$country','$gst_number','$field_of_company','$profile_picture','$contact_information',' $date',' $date')";
        // echo json_encode($sql);
        // echo json_encode($data);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    
    public function update1($id, $data): bool
    {


        if (empty($data)) {
            echo "1";
            return true;
        }

        // $name = $data['name'];

        $mobile_number = $data['mobile_number'];
        $status = $data['status'];
        $sql = "UPDATE `users` SET  
  
        mobile_number = '$mobile_number',
        status = '$status'
          WHERE id = $id";
        // echo "<pre>"; print_r($sql);
        // echo "</pre>";
        $post = $this->db->query($sql);
        if (!$post)
            throw new Exception('Post does not exist for specified id');

        return $post;
    }


    // public function update1($id, $data): bool
    // {


    //     if (empty($data)) {
    //         echo "1";
    //         return true;
    //     }

    //     $user_name = $data['user_name'];

    //     $user_number = $data['user_number'];
    //     $status = $data['status'];
    //     $sql = "UPDATE `user_log` SET  
    //     user_name = '$user_name',
    //     user_number = '$user_number',
    //     status = '$status'
    //       WHERE user_id = $id";
    //     // echo "<pre>"; print_r($sql);
    //     // echo "</pre>";
    //     $post = $this->db->query($sql);
    //     if (!$post)
    //         throw new Exception('Post does not exist for specified id');

    //     return $post;
    // }


    public function delete_w_ex($id)
    {
        // echo json_encode($data);


        $sql = "DELETE FROM `working_experiences` WHERE id= '$id'";
        // echo json_encode($sql);
        // echo json_encode($data);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
}
