<?php

namespace App;

class Bootstrap
{
    public static function boot()
    {
        self::set_autoload_function();
        
        self::set_enviroment();
    }
    
    public static function set_autoload_function()
    {
        //override spl autoload
    }
    
    public static function set_enviroment()
    {
        //get AppConfig
        
            //set error reporting
        
            //set server date zone
    }
}

