<?php

namespace k23\WebApp;

class DataConfig
{
    protected static $data = [];
    
    public static function get($key)
    {
        if(array_key_exists($key, self::getData())) {
            return self::$data[$key];
        } else {
            throw new \Exception('Not existing DataConfig key: '.$key);
        }
    }
    
    public static function set($key, $value)
    {
        self::$data[$key]= $value;
    }
    
    public static function getData()
    {
        return self::$data;
    }
}
