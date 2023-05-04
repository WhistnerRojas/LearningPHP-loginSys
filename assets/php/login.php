<?php

include_once("../../classes/core/Request.php");

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array(
        'msg' => 'invalid'
    ));
    exit();
}
// else{
//     echo json_encode(array(
//         'msg' => 'valid'
//     ));
//     // session_start();
//     // $_SESSION['unique_id'] = 1;
// }

$email = $_POST['email'] ?? '';
$pass = trim($_POST['pass']) ?? '';
$logUser = new Request($email, $pass);
echo json_encode($logUser->getUsers());


// Set the response content type to JSON
// header('Content-Type: application/json');
// // Create a PHP array or object
// $data = array(
//             'name' => 'John Doe',
//             'pass' => $pass,
//             'email' => $email
//         );

// // Encode the data as a JSON string
// $json = json_encode($data);

// // Send the JSON string to the client
// print $json;