<?php
    class View{
        function __construct()
        {
         
        }

         function render($controller,$nombre){
            
            require $controller.'/views/'.$nombre.'.php';
        }
    }
?>