<?php

include_once("../../classes/core/Register.php");
include_once("../../classes/core/Request.php");

$fname = $_POST["fname"];
$lname = $_POST['lname'];
$email = $_POST['email'];
$pass = trim($_POST['pass']);
$profilePic = $_FILES['image'];

if(isset($fname, $lname, $email, $pass, $profilePic)){

    $register = new Register($fname, $lname, $email, $pass, $profilePic);

    if($register->checkUserExists() === false){
        if($register->UploadProfilePic()){
            $logUser = new Request($email, $pass);
            $logUser->getUser();
            echo json_encode(array(
                'msg' => 'Successfully registered.',
                'msg2' => 'valid'
            ));
        }else{
            echo json_encode(array('msg' => 'An error occurred. Please try again!'));
        }
    }else{
        echo json_encode(array('msg' => 'Email already exist!'));
    }

}