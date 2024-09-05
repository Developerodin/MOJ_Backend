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
    public function get_data_id($Id)
    {
        // echo "test";
        $builder = $this->db->table('working_experiences');
        $builder->select(' working_experiences.*');
        $builder->where('working_experiences.id', $Id);

        $query = $builder->get();

        $user = $query->getResult();

        $result = $user;
        return $result;
    }
    public function get_auser()
    {
        // echo "test";
        $builder = $this->db->table('users');
        $builder->select(' users.*');
        $builder->where('users.work_ex', 'Agent');

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
        $builder->select('user_profiles.*');

        $builder->where('user_profiles.user_id', $userId);
        $query = $builder->get();

        $user = $query->getResult();


        if (!$user) {
            return null;
        } else {
            return $user[0];
        }
    }
    
    public function getHUserData($userId)
{
    $builder = $this->db->table('hoteliers');
        $builder->select('hoteliers.*');

        $builder->where('hoteliers.user_id', $userId);
        $query = $builder->get();

        $user = $query->getResult();


        if (!$user) {
            return null;
        } else {
            return $user[0];
        }
   
}
public function getrefData($refId)
{
    // Step 1: Find all users with the given ref_id.
    $builder = $this->db->table('users');
    $builder->select('*');
    $builder->where('ref_id', $refId);
    $query = $builder->get();
    $users = $query->getResult();

    // If no users found, return null
    if (empty($users)) {
        return null;
    }

// print_r($users);
// die();



    $result = [];

    // Step 2: Loop through each user to find data in other tables
    foreach ($users as $user) {
        $userId = $user->id;
        // $userData = ['user' => $user];

        // Step 3: Search in 'user_profiles' table
        $profileBuilder = $this->db->table('user_profiles');
        $profileBuilder->select('user_profiles.role, user_profiles.user_id, user_profiles.name, user_profiles.created_at');
        $profileBuilder->where('user_id', $userId);
        $profileQuery = $profileBuilder->get();
        $userProfile = $profileQuery->getRow();

        if ($userProfile) {
            $userData['profile'] = $userProfile;
        } else {
            // Step 4: If not found in 'user_profiles', search in 'hoteliers' table
            $hotelierBuilder = $this->db->table('hoteliers');
            $hotelierBuilder->select('hoteliers.user_id, hoteliers.role, hoteliers.name, hoteliers.created_at');
            $hotelierBuilder->where('user_id', $userId);
            $hotelierQuery = $hotelierBuilder->get();
            $hotelierProfile = $hotelierQuery->getRow();

            if ($hotelierProfile) {
                $userData['profile'] = $hotelierProfile;
            } else {
                // Step 5: If not found in 'hoteliers', search in 'agent' table
                $agentBuilder = $this->db->table('agent');
                $agentBuilder->select('agent.user_id,agent.role,agent.name,agent.created_at');
                $agentBuilder->where('user_id', $userId);
                $agentQuery = $agentBuilder->get();
                $agentProfile = $agentQuery->getRow();

                if ($agentProfile) {
                    $userData['profile'] = $agentProfile;
                }
            }
        }

        // Add the user data to the results array
        $result[] = $userData;
    }

    // Return the final result
    return !empty($result) ? $result : null;
}
    public function getAUserData($userId)
{
    $builder = $this->db->table('agent');
        $builder->select('agent.*');

        $builder->where('agent.user_id', $userId);
        $query = $builder->get();

        $user = $query->getResult();


        if (!$user) {
            return null;
        } else {
            return $user[0];
        }
   
}
    public function getAllUserData()
    {
        $builder = $this->db->table('users');
        $builder->select('users.*, user_profiles.*');
        $builder->join('user_profiles', 'user_profiles.user_id = users.id');
        $query = $builder->get();
        $user = $query->getResult();
        // echo "<pre>";
        // print_r($user);
        // echo "</pre>";
        // die();
        if (empty($user)) {
            // echo "test";
            return false;
        } else {
            return $user;
        }
    }
    public function getUserCount()
    {
        $builder = $this->db->table('users');
        $builder->select('COUNT(*) as user_count');

        $query = $builder->get();
        $result = $query->getRow();

        return $result->user_count;
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


    public function getUserAData($userId)
    {

        $builder = $this->db->table('agent');
        $builder->select(' agent.*');

        $builder->where('agent.user_id', $userId);
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

        $mobile_number = $data['mobile_number'];
        // echo "<pre>"; print_r($mobile_number); echo "</pre>";
        // die();
        $status = "Enable";
        if($data['role'] == 'Employers'){
            $work_ex = "hotel";
        }elseif($data['role'] == 'Agent'){
            $work_ex = "Agent";
        }else{
            $work_ex = "fresher";
        }
        $ref_id = $data['ref_id'];
        $points = '0';
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date('Y-m-d H:i:s');

        $sql = "INSERT INTO `users`(`mobile_number`, `created_at`, `updated_at`, `last_active`, `points`,`ref_id`,`work_ex`, `status`) VALUES ('$mobile_number','$date1','$date1','$date1','$points','$ref_id','$work_ex','$status')";


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

        $user_id = $data['user_id'];
        $name = $data['name'];
        $last_name = $data['last_name'];
        $gender = $data['gender'];
        $pin_code = $data['pin_code'];
        $address = $data['address'];
        $dob = $data['dob'];
        $email = $data['email'];
        $role = $data['role'];
        $state = $data['state'];
        $city = $data['city'];
        $country = $data['country'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");


        $sql = "INSERT INTO `user_profiles`( `user_id`, `name`,`last_name`,`gender`,`address`,`pin_code`,`dob`, `email`,`role`, `state`, `city`, `country`, `created_at`, `updated_at`) VALUES ('$user_id','$name','$last_name','$gender','$address','$pin_code','$dob','$email','$role','$state','$city','$country','$date1','$date1')";


        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function update_profile($id, $data)
    {
        //    echo json_encode($sql);
        $user_id = $id;
        $name = $data['name'];
        $last_name = $data['last_name'];
        $pin_code = $data['pin_code'];
        $address = $data['address'];
        $dob = $data['dob'];
        $gender = $data['gender'];
        $email = $data['email'];
        $state = $data['state'];
        $city = $data['city'];
        $country = $data['country'];
       
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");

        $sql = "UPDATE `user_profiles` SET 
        `pin_code` = '$pin_code',       `address` = '$address',
        `dob` = '$dob',
        `name`='$name',`last_name`='$last_name',`gender`='$gender',`email`='$email',`state`='$state',`city`='$city',`country`='$country',`updated_at`='$date1' WHERE user_id = $user_id";
        // echo json_encode($sql);
        // echo ( $sql);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function update_status_e($id)
    {
        //    echo json_encode($sql);
        $user_id = $id;

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");

        $sql = "UPDATE `users` SET `status`='Enable',`updated_at`='$date1' WHERE id = $user_id";
        // echo json_encode($sql);
        // echo ( $sql);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function update_ref($id,$data)
    {
        //    echo json_encode($sql);
        $user_id = $id;
        $points= $data['point'];

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");

        $sql = "UPDATE `users` SET `points`='$points',`updated_at`='$date1' WHERE id = $user_id";
        // echo json_encode($sql);
        // echo ( $sql);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function update_status_d($id)
    {
        //    echo json_encode($sql);
        $user_id = $id;

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");

        $sql = "UPDATE `users` SET `status`='Disable',`updated_at`='$date1' WHERE id = $user_id";
        // echo json_encode($sql);
        // echo ( $sql);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }

    public function update_workex($data)
    {
        //    echo json_encode($sql);
        $id = $data['id'];
        $organisation = $data['organisation'];
        $department = $data['department'];
        $ref_mobile = $data['ref_mobile'];
        $ref_email = $data['ref_email'];
        $profile = $data['profile'];
        $state = $data['state'];
        $city = $data['city'];
        $sub_department = $data['sub_department'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");

        $sql = "UPDATE `working_experiences` SET 
        
        `ref_mobile` = '$ref_mobile',
        `ref_email`= '$ref_email',
        `state`= '$state',
        `city`= '$city',
        `organisation`='$organisation',`department`='$department',`profile`='$profile',`sub_department`='$sub_department',`start_date`='$start_date',`end_date`='$end_date',`updated_at`='$date1' WHERE id = $id";
        // echo json_encode($sql);
        // echo ( $sql);
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
         //echo json_encode($data);

        $user_id = $data['user_id'];
        $name = $data['name'];
        $location = $data['location'];
        $email = $data['email'];
        $address = $data['address'];
        $state = $data['state'];
        $pin_code = $data['pin_code'];
        $city = $data['city'];
        $role = $data['role'];
        $country = $data['country'];
        $gst_number = $data['gst_number'];
        $gst_name = $data['gst_name'];
        $reg_email = $data['reg_email'];
        $reg_hadd = $data['reg_hadd'];

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "INSERT INTO `hoteliers` (`user_id`,`name`,`location`,`email`,`address`,`state`,`pin_code`,`city`,`role`,`country`,`gst_number`,`gst_name`, `reg_email`,`reg_hadd`,`created_at`,`updated_at`) VALUES ('$user_id','$name','$location','$email','$address','$state','$pin_code','$city','$role','$country','$gst_number','$gst_name','$reg_email','$reg_hadd','$date1','$date1')";
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
    public function save_Aprofile($data)
    {
         //echo json_encode($data);

        $user_id = $data['user_id']; $name = $data['name'];
        $gender = $data['gender']; $email = $data['email'];
        $address = $data['address'];$state = $data['state'];
        $city = $data['city']; $pin_code = $data['pin_code'];
        $role = $data['role']; $dob = $data['dob'];
        $gst_number = $data['gst_number']; $gst_name = $data['gst_name'];
        
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "INSERT INTO `agent` (`user_id`,`name`,`gender`,`email`,`address`,`state`,`pin_code`,`city`,`role`,`dob`,`gst_number`,`gst_name`, `created_at`,`updated_at`) VALUES ('$user_id','$name','$gender','$email','$address','$state','$pin_code','$city','$role','$dob','$gst_number','$gst_name','$date1','$date1')";
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
    public function update_Aprofile($id,$data)
    {
         //echo json_encode($data);

        $user_id = $id; $name = $data['name'];
        $gender = $data['gender']; $email = $data['email'];
        $address = $data['address'];$state = $data['state'];
        $city = $data['city']; $pin_code = $data['pin_code'];
        $role = $data['role']; $dob = $data['dob'];
        $gst_number = $data['gst_number']; $gst_name = $data['gst_name'];
        
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "UPDATE `agent` SET `name`='$name',`gender`='$gender',`email`='$email',`address`='$address',`state`='$state',`pin_code`='$pin_code',`city`='$city',`role`='$role',`dob`='$dob',`gst_number`='$gst_number',`gst_name`='$gst_name',`updated_at`='$date1' WHERE user_id =$id ";
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
    public function update_hprofile($id,$data)
    {
         //echo json_encode($data);

  
        $name = $data['name'];
        $location = $data['location'];
        $email = $data['email'];
        $address = $data['address'];
        $state = $data['state'];
        $pin_code = $data['pin_code'];
        $city = $data['city'];
        $role = $data['role'];
        $country = $data['country'];
        $gst_number = $data['gst_number'];
        $gst_name = $data['gst_name'];
        $reg_email = $data['reg_email'];
        $reg_hadd = $data['reg_hadd'];

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "UPDATE `hoteliers` SET `name`='$name',`location`='$location',`email`='$email',`address`='$address',`state`='$state',`pin_code`='$pin_code',`city`='$city',`role`='$role',`country`='$country',`gst_number`='$gst_number',`gst_name`='$gst_name',`reg_email`='$reg_email',`reg_hadd`='$reg_hadd',`updated_at`='$date1' WHERE user_id =$id ";
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
    public function update_workex_up($data)
    {
        //    echo json_encode($sql);
        $id = $data['user_id'];
        $work_ex = $data['work_ex'];
        
        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");

        $sql = "UPDATE `users` SET 
        
       `work_ex`='$work_ex' WHERE id = $id";
        // echo json_encode($sql);
        // echo ( $sql);
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
        $department = $data['department'];
        $ref_mobile = $data['ref_mobile'];
        $ref_email = $data['ref_email'];
        $profile = $data['profile'];
        $state = $data['state'];
        $city = $data['city'];
        $sub_department = $data['sub_department'];
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];


        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "INSERT INTO `working_experiences`( `user_id`, `organisation`,`department`,`ref_mobile`,`ref_email`, `profile`, `sub_department`,`state`,`city`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES ('$user_id','$organisation','$department','$ref_mobile','$ref_email','$profile','$sub_department','$state','$city','$start_date','$end_date','$date1','$date1')";
        // echo json_encode($sql);
        // // // echo json_encode($data);
        // //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    // user edu

    public function getUserEd_id($userId)
    {

        $builder = $this->db->table('user_education');
        $builder->select('user_education.*');

        $builder->where('user_education.user_id', $userId);
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();

        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }
    public function edu_get_data_id($Id)
    {
        // echo "test";
        $builder = $this->db->table('user_education');
        $builder->select('user_education.*');
        $builder->where('user_education.id', $Id);

        $query = $builder->get();

        $user = $query->getResult();

        $result = $user;
        if ($result) {
            // Fetch the result set as an array of objects

            return $result;
        } else {
            // No rows found, return null or an empty array, depending on your preference
            return "not data get";
        }
    }

    public function getUserEd()
    {

        $builder = $this->db->table('user_education');
        $builder->select('user_education.*');
        $query = $builder->get();
        // Get the result
        $user = $query->getResult();

        if (!$user) {
            return null;
        } else {
            return $user;
        }
    }

    public function save_edu($data)
    {
        // echo json_encode($data);

            // echo "<pre>"; print_r($data); echo "</pre>";

      
        $user_id = $data['user_id'];
       
        $ten_th = $data['ten_th'];
      
        $ten_school = $data['ten_school'];
        $ten_year = $data['ten_year'];
        // echo "testing";
        $to_th = $data['to_th'];


        $to_th_school = $data['to_th_school'];


        $to_th_year = $data['to_th_year'];
        $gra_dip = $data['gra_dip'];
        $gr_degree = $data['gr_degree'];
        $gr_university = $data['gr_university'];
        $gr_year = $data['gr_year'];

        $post_gra = $data['post_gra'];
        $pg_degree = $data['pg_degree'];
        $pg_university = $data['pg_university'];
        $pg_year = $data['pg_year'];
        $doc = $data['doc'];
        $doc_degree = $data['doc_degree'];
        $doc_university = $data['doc_university'];
        $doc_year = $data['doc_year'];
       

        $hotel_de = $data['hotel_de'];
        $h_college = $data['h_college'];
        $h_year = $data['h_year'];

   


        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "INSERT INTO `user_education`(`user_id`, `ten_th`, `ten_school`, `ten_year`, `to_th`, `to_th_school`, `to_th_year`, `gra_dip`, `gr_degree`, `gr_university`, `gr_year`, `post_gra`, `pg_degree`, `pg_university`, `pg_year`, `doc`, `doc_degree`, `doc_university`, `doc_year`,`hotel_de`,`h_college`,`h_year`, `created_at`) VALUES ('$user_id',
        '$ten_th',
        '$ten_school',
        '$ten_year',
        '$to_th',
        '$to_th_school',
        '$to_th_year',
        '$gra_dip', '$gr_degree','$gr_university','$gr_year',
        '$post_gra',
        '$pg_degree',
        '$pg_university',
        '$pg_year',
        '$doc',
        '$doc_degree',
        '$doc_university',
        '$doc_year',
        '$hotel_de',
        '$h_college',
        '$h_year',
       '$date1')";
        // echo json_encode($sql);
        // // echo json_encode($data);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function save_edu_up($data)
    {
        // echo json_encode($data);


      
        $user_id = $data['user_id'];
        $ten_th = $data['ten_th'];
        $ten_school = $data['ten_school'];
        $ten_year = $data['ten_year'];
        $to_th = $data['to_th'];
        $to_th_school = $data['to_th_school'];
        $to_th_year = $data['to_th_year'];
        $gra_dip = $data['gra_dip'];

        $gr_degree = $data['gr_degree'];
        $gr_university = $data['gr_university'];
        $gr_year = $data['gr_year'];

        $post_gra = $data['post_gra'];
        $pg_degree = $data['pg_degree'];
        $pg_university = $data['pg_university'];
        $pg_year = $data['pg_year'];
        $doc = $data['doc'];
        $doc_degree = $data['doc_degree'];
        $doc_university = $data['doc_university'];
        $doc_year = $data['doc_year'];

        

        $hotel_de = $data['hotel_de'];
        $h_college = $data['h_college'];
        $h_year = $data['h_year'];

        $date = new DateTime();
        $date = date_default_timezone_set('Asia/Kolkata');
        $date1 = date("m-d-Y h:i A");
        $sql = "UPDATE `user_education` SET `ten_th`='$ten_th',`ten_school`='$ten_school',`ten_year`='$ten_year',`to_th`='$to_th',`to_th_school`='$to_th_school',`to_th_year`='$to_th_year',`gra_dip`='$gra_dip',`gr_degree`='$gr_degree',`gr_university`='$gr_university',`gr_year`='$gr_year',`post_gra`='$post_gra',`pg_degree`='$pg_degree',`pg_university`='$pg_university',`pg_year`='$pg_year',`doc`='$doc',`doc_degree`='$doc_degree',`doc_university`='$doc_university',`doc_year`='$doc_year',
        `hotel_de`='$hotel_de',`h_college`='$h_college',`h_year`='$h_year',
        
        `created_at`='$date1' WHERE user_id ='$user_id'";
        // echo json_encode($sql);
        // echo json_encode($sql);
        //     die();
        $post = $this->db->query($sql);

        if (!$post) {
            return false;
        } else {
            return $post;
        }
    }
    public function delete_edu($id)
    {
        // Prepare the SQL statement with a placeholder for the id
        $sql = "DELETE FROM `user_education` WHERE id = ?";

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



    
    // user delete

    public function delete_usweb($id)
    {
        // Prepare the SQL statement with a placeholder for the id
        $sql = "DELETE FROM `users` WHERE id = ?";

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

