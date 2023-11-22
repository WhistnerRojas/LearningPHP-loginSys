<?php
session_start();
include_once("../../classes/core/Request.php");

$message = $_POST['message'] ?? '';
$incomingId = $_POST['incoming_id'] ?? '';

if(isset($message) && isset($incomingId)){
    $sendChat = new Request('','');
    $sendChat->sendMsg($message, $incomingId, $_SESSION['unique_id']);
}