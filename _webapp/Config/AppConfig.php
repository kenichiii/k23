<?php

namespace App\Config;

use k23\WebApp\DataConfig;

class AppConfig extends DataConfig
{
    protected static $data = [                   
       'session_key' => 'dasasdasd01342304u9werhdfudshsdf0hsdfdfhsdfi[dh[zdisdsDFfdhie033093',  
        
       'user_grid' => '\\App\\Model\\User\\Grid',
        
       'reserved_uris' => [
         'api', '_cmd', '_component','_layout'  
       ], 
    ];
}

