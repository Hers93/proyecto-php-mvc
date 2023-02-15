<?php

spl_autoload_register(function ($clase) {
    print_r($clase);
    
    require_once 'src/'.str_replace('\\','/', $clase).'.php';
});