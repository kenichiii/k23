<?php

namespace App\Service\WebApp;

class AppSessionService
{
    private $session_key;
    
    public function __construct($session_key)
    {
       $this->session_key = $session_key; 
       
       @session_start();
       
       if(filter_input(INPUT_SESSION, $session_key) === null) {
           $_SESSION[$session_key]= [];
       }
    }
    
    public function get($name = null)
    {
        $session = filter_input(INPUT_SESSION, $this->session_key);
        return $name ? $session[$name] : $session;
    }
    
    public function set($name, $value = null)
    {
        if($name) {
            $_SESSION[$this->session_key][$name] = $value;
        } else {
            $_SESSION[$this->session_key] = $name;
        }        
    }
}

