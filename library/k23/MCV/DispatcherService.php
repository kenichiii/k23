<?php

namespace k23\MCV;

class DispatcherService
{
    protected $url;
    protected $controller;
    
    public function __construct($url) {
        $this->url = $url;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function getController()
    {
        if(!$this->controller) {
            $class = $this->getControllerName();
            $this->controller = new $class();
        }
        
        return $this->controller;
    }
    
    protected function getControllerName()
    {
        $url = $this->getUrl();
    }
    
    public function dispatch()
    {
        $url = $this->getUrl();
        $cache = ServicesContainer::ins('appcache');
        
        if($cache->isSuperCachedRequest($url)) {
            //read response from super fast cache
            $reponse = $cache->getSuperCachedRequest($url);
        } else {
            //check user rights -> connect db...

            if(!$cache->isCachedRequest($url)) {
                $response = $this->getConttroler()->getResponse();
                
                //serialize response into cache            
                $cache->set($url, $response);
            } else {
                //get response from cache
                $reponse = $cache->getCachedRequest($url);
            }
       }
       
       return $response;
    }
}
