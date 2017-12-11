<?php

namespace App\Service;

class DibiService
{
    protected $config;
    protected $connection;
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connection = new \DibiConnection($this->getConfig());
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    public function getConn()
    {
        return $this->connection;
    }        
}

