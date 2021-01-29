<?php

    require_once('../interfaces/account.interface.php');
    require_once('utility.class.php');



    class User implements Account{

        private $firstname, $lastname, $email, $phone, $user_id, $password;

        
        
        /**
         * The user name details are already set
         */
        public function register (PDO $pdo){
            //validating all data
            $errors = [];

            if(!preg_match('/^[A-Za-z\'-]$/', $this->firstname)){
                $errors[] = "fne"; //first name error
            }

            if(!preg_match('/^[A-Za-z\'-]$/', $this->lastname)){
                $errors[] = "lne"; //last name error
            }

            if(!preg_match('/^\+\d{1,3}(-\d{1,3})?\d{9}$/', $this->phone)){
                $errors[] = "pne"; //phone error
            }

            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
                $errors[] = "eme";
            }

            //check if email exist
            

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
                $errors[] = "pne"; //password number error
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

        public function login(PDO $pdo){
            $stmt = $pdo->prepare("SELECT * from user where email = ?");

        }

        public function changePassword(PDO $pdo){

        }

        public function logout (PDO $pdo){

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
    }




?>