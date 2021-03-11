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
                // echo "{$ex->getMessage()}";
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

         public static function returnImageSrc($user_id){
            $extArray = array("jpg", "jpeg", "png", "gif");
            foreach($extArray as $ext){
                 foreach(glob("../uploads/".$user_id."*".$ext) as $file){
                     return substr($file, 3);
                 }
            }
              return false;
         }

         /**
          * checks if an accepted image was uploaded
          */
         public static function is_image($path){
           $check = getimagesize($path);
           if(in_array($check[2], array('jpg', IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))){
               return true;
           }
           return false;
         }

         /**
          * Moves and image to the uploads directory
          */
         public static function uploadImage(&$image, $id, $update = false){
            $target_dir = "../uploads/";
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $ext = strtolower($ext);
            if($update){
                unlink("../".self::returnImageSrc($id));
            }
            switch (exif_imagetype($image['tmp_name'])) {
                case IMAGETYPE_PNG:
                    $imageTmp=imagecreatefrompng($image['tmp_name']);
                    break;
                case IMAGETYPE_JPEG:
                    $imageTmp=imagecreatefromjpeg($image['tmp_name']);
                    break;
                case IMAGETYPE_GIF:
                    $imageTmp=imagecreatefromgif($image['tmp_name']);
                    break;
                case IMAGETYPE_BMP:
                    $imageTmp=imagecreatefrombmp($image['tmp_name']);
                    break;
                // Defaults to JPG
                default:
                    $imageTmp=imagecreatefromjpeg($image['tmp_name']);
                    break;
            }
        
            // quality is a value from 0 (worst) to 100 (best)
            if(imagejpeg($imageTmp, $target_dir.$id."-".uniqid().".".$ext, 65)){
                imagedestroy($imageTmp);
                return true;
            }
            else{
                imagedestroy($imageTmp);
                return false;
            }
        }
        
    }


?>