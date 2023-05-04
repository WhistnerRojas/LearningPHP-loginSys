<?php

class Router{

    public $request;
    public $response;
    protected array $routes = [];

    // public function __construct(){
    //     $this->login = new Login();
    // }
    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
        // var_dump($path."<br/>");
    }

    public function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getPath(){
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if($position === false){
            return $path;
        }else{
            $path = strstr($path, '?');
            return $path;
        }
    }

    public function resolve(){ //getting the request
        $path = $this->getPath();
        $method = $this->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        $callback === false && $this->renderView('404');
        is_string($callback) && $this->renderView($callback);
    }

    public function renderView($view){ //rendering the page
        $contentTemplate = $this->contentTemplate();
        $viewContent =  $this->pageContent($view);
        // $viewContent = $this->login->getUsers() ?? 'No Users';
        echo str_replace('{{content}}', $viewContent, $contentTemplate);
    }

    protected function contentTemplate() {
        //gets the template or body of the html tags
        $path = Application::$ROOT_DIR."/view/main.php";
        ob_start();
        include $path;
        return ob_get_clean();
    }
    
    protected function pageContent($view) {
        //get the path of the page/view file
        $path = Application::$ROOT_DIR."/view/template/$view.php";
        ob_start();
        include $path;
        return ob_get_clean();
    }
}