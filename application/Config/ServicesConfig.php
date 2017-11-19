<?php

namespace App\Config;

use k23\App\DataConfig;

class ServicesConfig extends DataConfig
{
    protected static $_data = [
        
      'db' => [
          'servicename' => 'DibiService', 
          'data' => [
             'servicename' => 'DibiService',
             'host' => 'localhost',
             'username' => 'root',
             'passowrd' => '',
             'database' => 'cms_framework',
             'charset' => 'UTF-8',
          ],                  
        ],
        
      'infomail' => [
          'servicename' => 'InfoMailService',
          'data' => 'kena1@email.cz',
      ],
        
       //stmp mail settings 
      
      'session' => [
          'servicename' => 'AppSessionService',
          'data' => 'some-very-unusual-string',
      ],  
        
      'user' => [
        'servicename' => 'AppUserService',
        'data' => null,  
      ], 
        
      'img' => [
        'servicename' => 'MagickImageService',
        'data' => [
           'cache_dir' => \App\DATA_PATH . '/img_cache', 
           'allowed_resolutions' => ['50x50'],  
        ],  
      ],  
    ];
}
