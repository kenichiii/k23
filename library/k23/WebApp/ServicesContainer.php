<?php

namespace k23\WebApp;

use App\Config\ServicesConfig;

class ServicesContainer
{
    protected static $_instances = [];
    
    public static function ins($name)
    {
        if(!array_key_exists($name, self::$instances))
        {           
            $config = ServicesConfig::get($name);
            
            if(array_key_exists('usecall', $config) && $config['usecall']) {
                //call class from call class function with parameters
            } else {
                self::$_instances[$name]= new $config['servicename']($config['data']);
            }            
        }
        
        return self::$_instances[$name];
    }    
}