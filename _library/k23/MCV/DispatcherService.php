<?php

namespace k23\MCV;

class DispatcherService
{
    protected $url;
    protected $controller;
    
    protected $controller_name;
    protected $lang;
    
    protected $isPageRequest;
    protected $isApiRequest;
    protected $isComponentRequest;
    
    protected $isActionRequest;
    protected $isAjaxRequest;
    protected $isBaseRequest;
    
    public function __construct($url)
    {
        $this->url = $url;
        $this->parseUrl();
    }
    
    protected function parseUrl()
    {
        $url = $this->getUrl();
        //get lang
        //get base url
        //get controller
        if(!$this->controller) {
            $class = $this->getControllerName();
            $this->controller = new $class();
        }       
        //get params
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function getController()
    {        
        return $this->controller;
    }
    
    protected function getControllerName()
    {
        $url = $this->getUrl();
    }
    
    public function dispatch()
    {
        $url = $this->getUrl();        
        
        if(MagickImageService::isMagickImageRequest($url))
        {
            $magick_image = ServicesContainer::ins('magick_img');
            $response = $magick_image->dispatch($url);
        }
        elseif($cache->existsSuperCachedResponse($url)) {
            //read response from super fast cache
            $cache = ServicesContainer::ins('cache');
            $reponse = $cache->getSuperCachedResponse($url);
        } else {
            //check user rights -> connect db...
            
            $cache = ServicesContainer::ins('cache');            
            if(!$cache->existsCachedRequest($url)) {
                $response = $this->getConttroler()->dispatch();
                
                //serialize response into cache            
                if($response->isSuperCacheResponse())
                {
                    $cache->setSuperCachedResponse($response);
                } elseif($response->isCachedResponse()) {
                    $cache->setCachedResponse($response);
                }
            } else {
                //get response from cache
                $reponse = $cache->getCachedResponse($url);
            }
       }
       
       return $response;
    }
}
