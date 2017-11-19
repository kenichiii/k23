<?php

namespace k23\WebApp;

class DataConfig
{
    protected static $_data = [];
    
    public static function get($key)
    {
        if(array_key_exists($key, self::getData())) {
            return self::$_data[$key];
        } else {
            throw new \Exception('Not existing DataConfig key: '.$key);
        }
    }
    
    public static function set($key, $value)
    {
        self::$_data[$key]= $value;
    }
    
    public static function getData()
    {
        return self::$_data;
    }
}
