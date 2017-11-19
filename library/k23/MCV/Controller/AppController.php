<?php

namespace k23\MCV;

use \k23\App\ServicesContainer;

abstract class AppController
{
    const RESPONSE_CLASS_NAME = '\\k23\\MCV\\View\\AppResponse';
    protected $response;
    
    public function dispatch()
    {
        $this->beforeIndexAction();
        $this->indexAction();
        $this->afterIndexAction();
        
        return $this->getResponse();
    }
    
    abstract protected function indexAction();
    
    protected function beforeIndexAction()
    {
        //silent is golden
    }
    
    protected function afterIndexAction()
    {
        //silent is golden
    }    
    
    protected function sc($key)
    {
        return ServicesContainer::ins($key);
    }
    
    protected function getResponse() 
    {
        if(!$this->response) {
            $class = self::RESPONSE_CLASS_NAME;
            $this->response = new $class();
        }
        
        return $this->response;
    }
}

