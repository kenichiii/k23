<?php

namespace App\Config;

use k23\WebApp\DataConfig;

class ServicesConfig extends DataConfig
{
    protected static $data = [
        
      'db' => [
          'class' => '\\App\\Service\\DibiService', 
          'data' => [
            'config' => [  
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'database' => 'cms_framework',
                'charset' => 'UTF-8',
            ]
          ],                  
        ],
        
      'infomail' => [
          'class' => '\\App\\Service\\SendMailService',
          'data' => ['from' => 'kena1@email.cz'],
      ],
        
       //stmp mail settings 
      
      'session' => [
          'class' => '\\App\\Service\\WebApp\\AppSessionService',
          'data' => ['token' => 'some-very-unusual-string'],
      ],  
        
      'user' => [
        'class' => '\\App\\Service\\WebApp\\AppUserService',
        'data' => [],  
      ], 
        
      'img' => [
        'class' => '\\App\\Service\\MagickImageService',
        'data' => [
           'cache_dir' => \App\DATA_PATH . '/img_cache', 
           'allowed_resolutions' => ['50x50'],  
        ],  
      ],  
    ];
}
