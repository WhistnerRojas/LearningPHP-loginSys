<?php

include_once("../../classes/core/Request.php");

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array(
        'msg' => 'invalid'
    ));
    exit();
}

$email = $_POST['email'] ?? '';
$pass = trim($_POST['pass']) ?? '';
$logUser = new Request($email, $pass);

if($logUser->getUser() === true){
    echo json_encode(array('msg' => 'valid'));
}else{
    echo json_encode(array('msg' => 'invalid'));
}