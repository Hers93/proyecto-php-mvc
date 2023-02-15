<?php
  /*   require_once './utilerias/error/error.php'; */
    class App {
        function __construct()
        {
            
            $url = $_GET['url'];
            $url = rtrim($url,'/');
            $url = explode('/',$url);
            $controller = $url[0].'Controller';
            $controller = ucfirst($controller);
            $path = './src/'.ucfirst($url[0]).'/'.'Controllers/'. $controller.'.php';
            if(file_exists($path)){
                require_once $path ;
                $class = new $controller;
                if(isset($url[1])){
                    $class->{$url[1].'Action'}();
                } else{
                    $class->{$url[0].'Action'}();
                }
            }
            /* else{
                $error = new Error();   
            } */
           
        }
    }
  
            
    
           
   
    

           
            
            