<?php
session_start();

require_once("../interfaces/account.interface.php");
require_once('../classes/utility.class.php');
require_once('../classes/user.class.php');


    $firstname = trim(filter_var($_POST['firstname'], FILTER_SANITIZE_STRING));
    $lastname = trim(filter_var($_POST['lastname'], FILTER_SANITIZE_STRING));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $phone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_STRING));
    $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));

    $profile_picture = $_FILES['profile_picture'];

    $editing = false;
    $addpicture = false;
    if(isset($_POST['action'])){
        $editing = true;
        $old_password = trim(filter_var($_POST['old-password'], FILTER_SANITIZE_STRING));
        
        if($profile_picture){
            $addpicture = true;
        }
    }

    if(empty($profile_picture)){
        if(!$editing){
            echo "aie";//add image error
            exit;
        }
       
    }

    if(!Utility::is_image($profile_picture['tmp_name'])){
        if(($editing && $addpicture) || !$editing){
            echo "ie";
            exit;
        }   
    }

    if($editing){
        $user_id = $_SESSION['user_id'];
        
    }

    //verifying password
    if($editing){
        $conn = Utility::makeConnection();
        $user = new User($_SESSION['user_id']);

        if(!empty($old_password)){
            //password change request was made
            $user->setTestOldPassword($old_password);
            $user->setNewPassword($password);  
            $password_changed = $user->changePassword($conn);
            if($password_changed !== true){
                echo $password_changed;
                $conn = null;
                exit;
            }  
        }

        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPhone($phone);

        if($user->persist($conn)){
            if($addpicture){
                Utility::uploadImage($profile_picture, $user->getUser_id(), true);
            }
            echo "yes";
        }else{
            echo "no";
        }
        
        $conn = null;
        exit;
    }
    else{

        $newUser = new User();
        $newUser->setFirstname($firstname);
        $newUser->setLastname($lastname);
        $newUser->setEmail($email);
        $newUser->setPassword($password);
        $newUser->setPhone($phone);
    }
    

    $conn = Utility::makeConnection();
    $register = $newUser->register($conn);
    if($register === true){
        if(Utility::uploadImage($profile_picture, $newUser->getUser_id(), $editing)){
            echo "yes";
        }else{
            if($newUser->deleteUser($conn)){
                echo "no";
            }else{
                echo "no";
            }
           
        }
    }else if(is_array($register)){
        echo $register[0];
    }else{
        echo "no";
    }

    $conn = null;
    exit;

?>