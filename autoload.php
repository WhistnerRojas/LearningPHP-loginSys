<?php

spl_autoload_register('AutoLoaderClasses');

function AutoLoaderClasses($classname){
    // $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url = $_SERVER['HTTP_HOST']."signinup/";

    strpos($url, "classes") !== false ? $folder = "../classes/core/" : $folder = "../classes/core/";

    $ext = ".php";
    $path = $folder.$classname.$ext;
    // var_dump($path);
    if(!file_exists($path)){
        return 0;
    }else{
        require_once $path;
    }
}