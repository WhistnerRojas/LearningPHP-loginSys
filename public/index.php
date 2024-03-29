<?php

include_once '../autoload.php';

$url = $_SERVER['REQUEST_URI'];
$app = new Application(dirname((__DIR__)));

session_start();
if(!isset($_SESSION['unique_id'])){
    $app->router->get("$url", 'login');
    $app->router->get("?page=signup", 'signup');
    $app->router->get("?page=404", '404');
}else{
    $app->router->get("$url", 'users');
    if(isset($_GET['user_id'])){
        $userId = $_GET['user_id'];
        $app->router->get('?user=chat&user_id='.$userId , 'chat');
    }
    $app->router->get("?user=logout", 'logout');
}

$app->run();