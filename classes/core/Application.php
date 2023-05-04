<?php

class Application{
    public Router $router;
    public static Application $app;
    public static string $ROOT_DIR;
    public function __construct($rootPath){
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->router = new Router();
    }
    public function run(){
        $this->router->resolve();
    }
}