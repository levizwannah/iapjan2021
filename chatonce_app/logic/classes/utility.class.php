<?php

    class Utility{
        public static $dbuser="root", $dbname = "chat_once", $dbpass = "", $hostname = "localhost";

        public static function makeConnection($options = false){
            $dsn = "mysql:host=". self::$hostname . ";dbname=". self::$dbname;
            if(!$options){
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ];
            }

            try{

                $pdo_conn = new PDO($dsn, self::$dbuser, self::$dbpass, $options);
                return $pdo_conn;
            }
            catch(PDOException $ex){
                return $ex->getMessage();
            }
            
        }

        /**
         * check if an email exist already.
         * Make sure the email is valid before checking its existence
         */

         public static function emailExist($email){
             $pdo = self::makeConnection();
             $stmt = $pdo->prepare("SELECT `user_id` from user where email = ?");
             $stmt->execute([$email]);
             if(count($stmt->fetchAll()) > 0){
                 $exist = true;
             }else{
                 $exist = false;
             }

             $stmt = null;
             $pdo = null;

             return $exist;
         }
    }


?>