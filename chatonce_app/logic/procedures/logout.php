<?php
session_start();

require_once('../classes/utility.class.php');
require_once('../classes/user.class.php');
$currentUser = new User();

if($currentUser->logout(Utility::makeConnection())){
    echo "yes";
}else{
    echo "no";
}



?>