<?php
session_start();

require_once("../interfaces/account.interface.php");
require_once('../classes/utility.class.php');
require_once('../classes/user.class.php');

    $email = trim(filter_var($_POST['e'], FILTER_SANITIZE_EMAIL));
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo "eie";//email invalid error
        exit;
    }

    $password = trim(filter_var($_POST['p'], FILTER_SANITIZE_STRING));

    $user = new User();
    $user->setEmail($email);
    $user->setPassword($password);

    $conn = Utility::makeConnection();
    $loggedIn = $user->login($conn);

    if($loggedIn === true){
        $_SESSION['user_id'] = $user->getUser_id();
        echo "yes";
    }else{
        echo $loggedIn;
    }

    $conn = null;
    


?>