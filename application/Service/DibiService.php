<?php

namespace App\Service;

use App\Config\ServicesConfig;

class DibiService extends \DibiConnection
{
    protected $_config;
    
    public function __construct(array $config)
    {
        $this->_config = $config;
        parent::__construct($config);        
    }
    
    public function getConfigArray()
    {
        return $this->_config;
    }
}

