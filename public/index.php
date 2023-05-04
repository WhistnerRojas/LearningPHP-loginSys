<?php

include_once '../autoload.php';

$url = $_SERVER['REQUEST_URI'];
$app = new Application(dirname((__DIR__)));

session_start();
if(!isset($_SESSION['unique_id'])){
    $app->router->get("$url", 'login');
    $app->router->get("?page=signup", 'signup');
}else{
    $app->router->get("$url", 'users');
    $app->router->get("?user=logout", 'logout');
}
$app->router->get("?page=404", '404');

$app->run();