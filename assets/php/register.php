<?php

include_once("../../classes/core/Register.php");

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$profilePic = $_POST['profilepic'];

$register = new Register($fname, $lname, $email, $pass, $profilePic);

echo json_encode(array('msg' => 'invalid'));