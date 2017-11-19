<?php

class AppResponse
{
    protected $lang;
    
    protected $headers = [];
    protected $body;
    
    protected $data = [];
    
    final public function __construct($lang)
    {
        $this->lang = $lang;
    }
    
    public function getLang()
    {
        return $this->lang;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function addData(array $data)
    {
        array_merge_recursive($this->data, $data);
    }
    
    public function setData(array $data)
    {
        $this->data = $data;
    }
    
    public function getHeaders() 
    { 
        return $this->headers;         
    }
    
    public function getBody() 
    { 
        return $this->body;     
    }
    
    public function addHeader($declaration)
    {
        $this->headers[]= $declaration;
    }
    
    public function setBody($body)
    {
        $this->body = $body;
    }
}

