<?php
    session_start();
    $sessionId = $_SESSION['unique_id'];
    include_once("../../classes/core/Request.php");
    if(isset($_GET['users']) && $_GET['users'] === "online"){
        $getAllUser = new Request('','');
        echo $getAllUser->getAllUser($sessionId);
    }

    if(isset($_GET['user_id'])){
        $getAllUser = new Request('','');
        echo $getAllUser->getMessages($sessionId, $_GET['user_id']);
    }

    if(isset($_POST['search'])){
        $searchTerm = $_POST['search'];
        $search = new Request('','');
        echo $search->searchChatUser($searchTerm, $sessionId);
    }
