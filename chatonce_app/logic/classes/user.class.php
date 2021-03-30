<?php

    //require_once('../interfaces/account.interface.php');
    //require_once('utility.class.php');



    class User implements Account{

        private $firstname, $lastname, $email, $phone, $user_id, $password;

        //when password change is to be made
        private $new_password, $old_password;
        //when a property that doesn't exist in this object gets set or gotten.
        private $runtimeAttritubes = [];

        /**
         * If the user Id is passed, the pdo must also be passed.
         * Passing the user Id means constructing a user with all details from the database.
         * If no user Id is passed, then an empty user is created.
         * @return void
         */
        public function __construct($user_id = false, PDO $pdo = null)
        {
            //Todo
            if($user_id && $pdo){
                $stmt = $pdo->prepare("SELECT * from user where `user_id` = ?");
                $stmt->execute([$user_id]);
                $result = $stmt->fetch();
                $this->firstname = $result['firstname'];
                $this->lastname = $result['lastname'];
                $this->phone = $result['phone'];
                $this->email = $result['email']; 
                //the password is hashed
                $this->password = $result['password'];
                $this->user_id = $result['user_id'];
            }
        }
        
        /**
         * The user details are already set
         * @return bool|array of errors
         */
        public function register (PDO $pdo){
            //validating all data
            $errors = [];

            if(!preg_match('/^[A-Za-z\'-]+$/', $this->firstname)){
                $errors[] = "fne"; //first name error
            }

            if(!preg_match('/^[A-Za-z\'-]+$/', $this->lastname)){
                $errors[] = "lne"; //last name error
            }

            if(!preg_match('/^\+\d{1,3}(-\d{1,3})?\d{9}$/', $this->phone)){
                $errors[] = "pne"; //phone error
            }

            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
                $errors[] = "eme";
            }

            //check if email exist
            if(Utility::emailExist($this->email)){
                $errors[] = "emee";//email exist error
            }

            if(strlen($this->password) < 9){
                $errors[] = "ple"; //password length error
            }

            if(!preg_match("/[A-Z]/", $this->password)){
                $errors[] = "puce"; //password uppercase error
            }

            if(!preg_match("/[a-z]/", $this->password)){
                $errors[] = "plce"; //password lowercase error
            }

            if(!preg_match("/\d/", $this->password)){
                $errors[] = "pwne"; //password number error
            }

            //if no errors, proceed
            if(count($errors) < 1){


                $stmt = $pdo->prepare("INSERT INTO user(firstname, lastname, email, phone, `password`) values (?, ?, ?, ?, ?)");
                //hash the password
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                ($stmt->execute([$this->firstname, $this->lastname, $this->email, $this->phone, $this->password]))?$succeeded = true: $succeeded = false;
                if($succeeded){
                    //set the id of this object
                    $this->user_id = $pdo->lastInsertId();
                }
                $stmt = null;
            }else{
                $succeeded = false;
            }

            if($succeeded){
                return true;
            }
            else if(count($errors) > 0){
                return $errors;
            }
            else{
                return false;
            }

        }

        /**
         * Logs a user in to the system
         * the user email and password must be set before this method is called.
         * String return: uee (user exist error: the user does not exist).
         * String return: pwe (password error: the user password is wrong but they exist).
         * @return string|bool
         */
        public function login(PDO $pdo){
            $stmt = $pdo->prepare("SELECT * from user where email = ?");
            $stmt->execute(array($this->email));
            $result = $stmt->fetch();
            $stmt = null;
            if($result && count($result) > 0){
                //the user exist
                //so check the password
                if(password_verify($this->password, $result['password'])){
                    //define all the remaining attributes of this object
                    $this->firstname = $result['firstname'];
                    $this->lastname = $result['lastname'];
                    $this->phone = $result['phone'];
                    $this->email = $result['email']; 
                    $this->password = $result['password'];
                    $this->user_id = $result['user_id'];
                    return true;
                }
                else{
                    //return password error
                    return "pwe";//password error;
                }
            }else{
                //the user doesn't exist
                return "uee"; //user exist error
            }

        }

        /**
         * The new password attribute must be set before this method is called.
         * @return bool
         */
        public function changePassword(PDO $pdo){
            if(password_verify($this->old_password, $this->password)){
                //check the new password strength
                $new_password = $this->new_password;

                if(strlen($new_password) < 9){
                    $errors[] = "ple"; //password length error
                }
    
                if(!preg_match("/[A-Z]/", $new_password)){
                    $errors[] = "puce"; //password uppercase error
                }
    
                if(!preg_match("/[a-z]/", $new_password)){
                    $errors[] = "plce"; //password lowercase error
                }
    
                if(!preg_match("/\d/", $new_password)){
                    $errors[] = "pne"; //password number error
                }

                if($errors && count($errors) > 0){
                    return $errors;
                }else{
                    //hash the new password
                    $new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE user set `password`=? where `user_id` = ?");
                    if($stmt->execute(array($new_password, $this->user_id))){
                        //update the current password
                        $this->password = $new_password;
                        $succeeded = true;
                    }else{
                        $succeeded = false;
                    }
                }

                $stmt = null;
                return $succeeded;
            }
        }

        /**
         * If the user is logged in, this function logs them out
         * @return bool
         */
        public function logout (PDO $pdo){
            if(session_status() == PHP_SESSION_ACTIVE){
                session_destroy();
                return true;
            }else{
                return false;
            }
        }

        public function deleteUser(PDO $pdo){
            $stmt = $pdo->prepare("DELETE from user where `user_id` = ?");
            if($stmt->execute([$this->user_id])){
                $image = Utility::returnImageSrc($this->user_id);
            if(file_exists($image) && $image != "../"){
                unlink($image);
            }
               $return = true;
            }else{
                $return = false;
            }
            $stmt = null;
            return $return;
        }

        /**
         * get a runtime attribute
         */
        public function __get($name)
        {
            if(array_key_exists($name, $this->runtimeAttritubes)){
                return $this->runtimeAttritubes[$name];
            }else{
                return null;
            }
        }

        /**
         * sets a runtime attribute
         */

        public function __set($name, $value)
        {
            $this->runtimeAttritubes[$name] = $value;
        }


        /**
         * Get the value of firstname
         */ 
        public function getFirstname()
        {
                return $this->firstname;
        }

        /**
         * Set the value of firstname
         *
         * @return  self
         */ 
        public function setFirstname($firstname)
        {
                $this->firstname = $firstname;

                return $this;
        }

        /**
         * Get the value of lastname
         */ 
        public function getLastname()
        {
                return $this->lastname;
        }

        /**
         * Set the value of lastname
         *
         * @return  self
         */ 
        public function setLastname($lastname)
        {
                $this->lastname = $lastname;

                return $this;
        }

        /**
         * Get the value of email
         */ 
        public function getEmail()
        {
                return $this->email;
        }

        /**
         * Set the value of email
         *
         * @return  self
         */ 
        public function setEmail($email)
        {
                $this->email = $email;

                return $this;
        }

        /**
         * Get the value of phone
         */ 
        public function getPhone()
        {
                return $this->phone;
        }

        /**
         * Set the value of phone
         *
         * @return  self
         */ 
        public function setPhone($phone)
        {
                $this->phone = $phone;

                return $this;
        }

        /**
         * Get the value of user_id
         */ 
        public function getUser_id()
        {
                return $this->user_id;
        }

        /**
         * Get the value of password
         */ 
        public function getPassword()
        {
                return $this->password;
        }

        /**
         * Set the value of password
         *
         * @return  self
         */ 
        public function setPassword($password)
        {
                $this->password = $password;

                return $this;
        }

        public function setNewPassword($newPassword){
            $this->new_password = $newPassword;
        }

        public function setTestOldPassword($old_password){
            $this->old_password = $old_password;
        }

        /**
         * Persist this object to the database
         * @return bool
         */
        public function persist(PDO $pdo){
            $stmt = $pdo->prepare("UPDATE user set firstname = ?, lastname = ?, email = ?, phone = ? where  `user_id` = ?");
            if($stmt->execute(array($this->firstname, $this->lastname, $this->email, $this->phone))){
                $succeeded = true;
            }else{
                $succeeded = false;
            }

            $stmt = null;
            return $succeeded;
        }
    }




?>