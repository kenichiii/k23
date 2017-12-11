<?php

namespace App\Config;

use k23\WebApp\DataConfig;

class ProjectConfig extends DataConfig
{
    protected static $data = [
      'name' => 'SandboxApp',
      'copy' => '<a href="www.kena23.cz">kena23.cz</a>', 
      'base_url' => 'http://localhost/cmsfw/',  
      'languages' => ['cz','en','de'],  
    ];
}

